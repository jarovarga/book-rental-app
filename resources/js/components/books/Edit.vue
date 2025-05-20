<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Edit Book</h1>
    </div>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div v-else-if="book">
      <form @submit.prevent="submitForm">
        <!-- Title field -->
        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input
            type="text"
            class="form-control"
            id="title"
            v-model="form.title"
            :class="{ 'is-invalid': errors.title }"
            required
          >
          <div v-if="errors.title" class="invalid-feedback">
            {{ errors.title }}
          </div>
        </div>

        <!-- Author field -->
        <div class="mb-3">
          <label for="author_id" class="form-label">Author</label>
          <select
            class="form-select"
            id="author_id"
            v-model="form.author_id"
            :class="{ 'is-invalid': errors.author_id }"
            required
          >
            <option value="">Select an author</option>
            <option v-for="author in authors || []" :key="author.id" :value="author.id">
              {{ author.surname }}, {{ author.name }}
            </option>
          </select>
          <div v-if="errors.author_id" class="invalid-feedback">
            {{ errors.author_id }}
          </div>
        </div>

        <!-- Borrowed status -->
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="is_borrowed" v-model="form.is_borrowed">
          <label class="form-check-label" for="is_borrowed">Is Borrowed</label>
        </div>

        <!-- Submit button -->
        <div class="mb-3">
          <button type="submit" class="btn btn-primary" :disabled="submitting">
            <span v-if="submitting" class="spinner-border spinner-border-sm me-2" role="status"></span>
            Update Book
          </button>
          <router-link :to="`/books/${book.id}`" class="btn btn-secondary ms-2">Cancel</router-link>
        </div>
      </form>
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
import { useAuthorStore } from '@/stores/authorStore';

export default {
  name: 'BookEdit',
  emits: ['flash'],

  setup(props, { emit }) {
    const route = useRoute();
    const router = useRouter();
    const bookStore = useBookStore();
    const authorStore = useAuthorStore();

    const form = ref({
      title: '',
      author_id: '',
      is_borrowed: false
    });

    const errors = ref({});
    const submitting = ref(false);

    const book = computed(() => bookStore.book);
    const authors = computed(() => authorStore.authors);
    const loading = computed(() => bookStore.loading || authorStore.loading);
    const error = computed(() => bookStore.error);

    const fetchData = async () => {
      try {
        await Promise.all([
          bookStore.fetchBook(route.params.id),
          authorStore.fetchAuthors()
        ]);

        // Initialize form with book data
        if (bookStore.book) {
          form.value = {
            title: bookStore.book.title,
            author_id: bookStore.book.author.id,
            is_borrowed: bookStore.book.is_borrowed
          };
        }
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    const submitForm = async () => {
      errors.value = {};
      submitting.value = true;

      try {
        await bookStore.updateBook(book.value.id, form.value);
        emit('flash', 'Book updated successfully.');
        router.push(`/books/${book.value.id}`);
      } catch (error) {
        console.error('Error updating book:', error);

        if (error.response?.status === 422) {
          // Validation errors
          errors.value = error.response.data.errors;
          emit('flash', 'Please correct the errors in the form.', 'danger');
        } else {
          emit('flash', 'Failed to update book.', 'danger');
        }
      } finally {
        submitting.value = false;
      }
    };

    onMounted(fetchData);

    return {
      book,
      form,
      errors,
      authors,
      loading,
      error,
      submitting,
      submitForm
    };
  }
}
</script>
