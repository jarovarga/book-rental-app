<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Authors</h1>
      <router-link to="/authors/create" class="btn btn-primary">Add New Author</router-link>
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Error message -->
    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <!-- Authors table -->
    <div v-else>
      <p v-if="(authors || []).length === 0">No authors found.</p>
      <table v-else class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Surname</th>
            <th>Books Count</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="author in authors || []" :key="author.id">
            <td>{{ author.name }}</td>
            <td>{{ author.surname }}</td>
            <td>{{ author.books_count || 0 }}</td>
            <td>
              <router-link :to="`/authors/${author.id}`" class="btn btn-info btn-sm">View</router-link>
              <router-link :to="`/authors/${author.id}/edit`" class="btn btn-warning btn-sm">Edit</router-link>
              <button @click="confirmDelete(author)" class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { onMounted, computed } from 'vue';
import { useAuthorStore } from '@/stores/authorStore';

export default {
  name: 'AuthorIndex',
  emits: ['flash'],

  setup(props, { emit }) {
    const authorStore = useAuthorStore();

    const authors = computed(() => authorStore.authors);
    const loading = computed(() => authorStore.loading);
    const error = computed(() => authorStore.error);

    const fetchAuthors = async () => {
      try {
        await authorStore.fetchAuthors();
      } catch (error) {
        console.error('Error fetching authors:', error);
      }
    };

    const confirmDelete = (author) => {
      if (confirm(`Are you sure you want to delete "${author.name} ${author.surname}"?`)) {
        deleteAuthor(author.id);
      }
    };

    const deleteAuthor = async (id) => {
      try {
        await authorStore.deleteAuthor(id);
        emit('flash', 'Author deleted successfully.');
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

    onMounted(fetchAuthors);

    return {
      authors,
      loading,
      error,
      confirmDelete
    };
  }
}
</script>
