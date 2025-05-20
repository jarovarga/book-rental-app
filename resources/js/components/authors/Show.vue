<template>
  <div>
    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div v-else-if="author">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ author.name }} {{ author.surname }}</h1>
        <div>
          <router-link :to="`/authors/${author.id}/edit`" class="btn btn-warning me-2">Edit</router-link>
          <button @click="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">Author Details</h5>
          <div class="row mb-2">
            <div class="col-md-3 fw-bold">Name:</div>
            <div class="col-md-9">{{ author.name }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-3 fw-bold">Surname:</div>
            <div class="col-md-9">{{ author.surname }}</div>
          </div>
        </div>
      </div>

      <h3 class="mb-3">Books by this Author</h3>
      <div v-if="author.books && author.books.length > 0">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="book in author.books" :key="book.id">
              <td>{{ book.title }}</td>
              <td>
                <span :class="['badge', book.is_borrowed ? 'bg-danger' : 'bg-success']">
                  {{ book.is_borrowed ? 'Borrowed' : 'Available' }}
                </span>
              </td>
              <td>
                <router-link :to="`/books/${book.id}`" class="btn btn-info btn-sm">View</router-link>
                <router-link :to="`/books/${book.id}/edit`" class="btn btn-warning btn-sm">Edit</router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <p v-else>No books found for this author.</p>

      <div class="mt-4">
        <router-link to="/authors" class="btn btn-secondary">Back to Authors</router-link>
      </div>
    </div>
    <div v-else class="alert alert-warning">
      Author not found.
    </div>
  </div>
</template>

<script>
import { onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthorStore } from '@/stores/authorStore';

export default {
  name: 'AuthorShow',
  emits: ['flash'],

  setup(props, { emit }) {
    const route = useRoute();
    const router = useRouter();
    const authorStore = useAuthorStore();

    const author = computed(() => authorStore.author);
    const loading = computed(() => authorStore.loading);
    const error = computed(() => authorStore.error);

    const fetchAuthor = async () => {
      try {
        await authorStore.fetchAuthor(route.params.id);
      } catch (error) {
        console.error('Error fetching author:', error);
      }
    };

    const confirmDelete = () => {
      if (confirm(`Are you sure you want to delete "${author.value.name} ${author.value.surname}"?`)) {
        deleteAuthor();
      }
    };

    const deleteAuthor = async () => {
      try {
        await authorStore.deleteAuthor(author.value.id);
        emit('flash', 'Author deleted successfully.');
        router.push('/authors');
      } catch (error) {
        console.error('Error deleting author:', error);

        if (error.response?.status === 409) {
          // Conflict - author has books
          emit('flash', 'Cannot delete author with associated books.', 'danger');
        } else {
          emit('flash', 'Failed to delete author.', 'danger');
        }
      }
    };

    onMounted(fetchAuthor);

    return {
      author,
      loading,
      error,
      confirmDelete
    };
  }
}
</script>
