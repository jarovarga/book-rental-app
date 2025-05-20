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

    <div v-else-if="book">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ book.title }}</h1>
        <div>
          <router-link :to="`/books/${book.id}/edit`" class="btn btn-warning me-2">Edit</router-link>
          <button @click="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">Book Details</h5>
          <div class="row mb-2">
            <div class="col-md-3 fw-bold">Author:</div>
            <div class="col-md-9">
              <router-link v-if="book.author" :to="`/authors/${book.author.id}`">
                {{ book.author.surname }}, {{ book.author.name }}
              </router-link>
              <span v-else>Loading author information...</span>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-3 fw-bold">Status:</div>
            <div class="col-md-9">
              <button
                @click="toggleBorrowed"
                :class="['btn', 'btn-sm', book.is_borrowed ? 'btn-danger' : 'btn-success']"
                :disabled="toggleLoading"
              >
                <span v-if="toggleLoading" class="spinner-border spinner-border-sm" role="status"></span>
                {{ book.is_borrowed ? 'Borrowed' : 'Available' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-4">
        <router-link to="/books" class="btn btn-secondary">Back to Books</router-link>
      </div>
    </div>
    <div v-else class="alert alert-warning">
      Book not found.
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useBookStore } from '@/stores/bookStore';

export default {
  name: 'BookShow',
  emits: ['flash'],

  setup(props, { emit }) {
    const route = useRoute();
    const router = useRouter();
    const bookStore = useBookStore();

    const toggleLoading = ref(false);

    const book = computed(() => bookStore.book);
    const loading = computed(() => bookStore.loading);
    const error = computed(() => bookStore.error);

    const fetchBook = async () => {
      try {
        await bookStore.fetchBook(route.params.id);
      } catch (error) {
        console.error('Error fetching book:', error);
      }
    };

    const toggleBorrowed = async () => {
      toggleLoading.value = true;
      try {
        await bookStore.toggleBorrowed(book.value.id);
        emit('flash', 'Book borrow status toggled successfully.');
      } catch (error) {
        console.error('Error toggling borrowed status:', error);
        emit('flash', 'Failed to toggle borrowed status.', 'danger');
      } finally {
        toggleLoading.value = false;
      }
    };

    const confirmDelete = () => {
      if (confirm(`Are you sure you want to delete "${book.value.title}"?`)) {
        deleteBook();
      }
    };

    const deleteBook = async () => {
      try {
        await bookStore.deleteBook(book.value.id);
        emit('flash', 'Book deleted successfully.');
        router.push('/books');
      } catch (error) {
        console.error('Error deleting book:', error);
        emit('flash', 'Failed to delete book.', 'danger');
      }
    };

    onMounted(fetchBook);

    return {
      book,
      loading,
      error,
      toggleLoading,
      toggleBorrowed,
      confirmDelete
    };
  }
}
</script>
