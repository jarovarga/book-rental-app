<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Create New Book</h1>
    </div>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else>
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
            Create Book
          </button>
          <router-link to="/books" class="btn btn-secondary ms-2">Cancel</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useBookStore } from '@/stores/bookStore';
import { useAuthorStore } from '@/stores/authorStore';

export default {
  name: 'BookCreate',
  emits: ['flash'],

  setup(props, { emit }) {
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

    const authors = computed(() => authorStore.authors);
    const loading = computed(() => authorStore.loading);

    const fetchAuthors = async () => {
      try {
        await authorStore.fetchAuthors();
      } catch (error) {
        console.error('Error fetching authors:', error);
        emit('flash', 'Failed to load authors.', 'danger');
      }
    };

    const submitForm = async () => {
      errors.value = {};
      submitting.value = true;

      try {
        const book = await bookStore.createBook(form.value);
        emit('flash', 'Book created successfully.');

        // Fetch the book with author details before navigating
        await bookStore.fetchBook(book.id);
        router.push(`/books/${book.id}`);
      } catch (error) {
        console.error('Error creating book:', error);

        if (error.response?.status === 422) {
          // Validation errors
          errors.value = error.response.data.errors;
          emit('flash', 'Please correct the errors in the form.', 'danger');
        } else {
          emit('flash', 'Failed to create book.', 'danger');
        }
      } finally {
        submitting.value = false;
      }
    };

    onMounted(fetchAuthors);

    return {
      form,
      errors,
      authors,
      loading,
      submitting,
      submitForm
    };
  }
}
</script>
