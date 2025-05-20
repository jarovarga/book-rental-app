<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Books</h1>
      <router-link to="/books/create" class="btn btn-primary">Add New Book</router-link>
    </div>

    <!-- Filter form -->
    <form @submit.prevent="applyFilters" class="row g-3 mb-4">
      <div class="col-md-3">
        <input type="text" v-model="filters.title" class="form-control" placeholder="Search by Title">
      </div>
      <div class="col-md-3">
        <select v-model="filters.author_id" class="form-select">
          <option value="">All Authors</option>
          <option v-for="author in authors || []" :key="author.id" :value="author.id">
            {{ author.surname }}, {{ author.name }}
          </option>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="filters.is_borrowed" class="form-select">
          <option value="">All Statuses</option>
          <option value="1">Borrowed</option>
          <option value="0">Available</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <button type="button" @click="resetFilters" class="btn btn-secondary">Reset</button>
      </div>
    </form>

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

    <!-- Books table -->
    <div v-else>
      <p v-if="(books || []).length === 0">No books found.</p>
      <table v-else class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Borrowed</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in books || []" :key="book.id">
            <td>{{ book.title }}</td>
            <td>{{ book.author.surname }}, {{ book.author.name }}</td>
            <td>
              <button
                @click="toggleBorrowed(book.id)"
                :class="['btn', 'btn-sm', book.is_borrowed ? 'btn-danger' : 'btn-success']"
                :disabled="toggleLoading === book.id"
              >
                <span v-if="toggleLoading === book.id" class="spinner-border spinner-border-sm" role="status"></span>
                {{ book.is_borrowed ? 'Borrowed' : 'Available' }}
              </button>
            </td>
            <td>
              <router-link :to="`/books/${book.id}`" class="btn btn-info btn-sm">View</router-link>
              <router-link :to="`/books/${book.id}/edit`" class="btn btn-warning btn-sm">Edit</router-link>
              <button @click="confirmDelete(book)" class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useBookStore } from '@/stores/bookStore';
import { useAuthorStore } from '@/stores/authorStore';

export default {
  name: 'BookIndex',
  emits: ['flash'],

  setup(props, { emit }) {
    const bookStore = useBookStore();
    const authorStore = useAuthorStore();

    const filters = ref({
      title: '',
      author_id: '',
      is_borrowed: ''
    });

    const toggleLoading = ref(null);

    const books = computed(() => bookStore.books);
    const authors = computed(() => authorStore.authors);
    const loading = computed(() => bookStore.loading);
    const error = computed(() => bookStore.error);

    const fetchData = async () => {
      try {
        await Promise.all([
          bookStore.fetchBooks(filters.value),
          authorStore.fetchAuthors()
        ]);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    const applyFilters = () => {
      bookStore.fetchBooks(filters.value);
    };

    const resetFilters = () => {
      filters.value = {
        title: '',
        author_id: '',
        is_borrowed: ''
      };
      bookStore.fetchBooks();
    };

    const toggleBorrowed = async (id) => {
      toggleLoading.value = id;
      try {
        await bookStore.toggleBorrowed(id);
        // Emit flash message to parent component
        emit('flash', 'Book borrow status toggled successfully.');
      } catch (error) {
        console.error('Error toggling borrowed status:', error);
        // Emit error message to parent component
        emit('flash', 'Failed to toggle borrowed status.', 'danger');
      } finally {
        toggleLoading.value = null;
      }
    };

    const confirmDelete = (book) => {
      if (confirm(`Are you sure you want to delete "${book.title}"?`)) {
        deleteBook(book.id);
      }
    };

    const deleteBook = async (id) => {
      try {
        await bookStore.deleteBook(id);
        // Emit flash message to parent component
        emit('flash', 'Book deleted successfully.');
      } catch (error) {
        console.error('Error deleting book:', error);
        // Emit error message to parent component
        emit('flash', 'Failed to delete book.', 'danger');
      }
    };

    onMounted(fetchData);

    return {
      books,
      authors,
      loading,
      error,
      filters,
      toggleLoading,
      applyFilters,
      resetFilters,
      toggleBorrowed,
      confirmDelete
    };
  }
}
</script>
