<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Edit Author</h1>
    </div>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div v-else-if="author">
      <form @submit.prevent="submitForm">
        <!-- Name field -->
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            v-model="form.name"
            :class="{ 'is-invalid': errors.name }"
            required
          >
          <div v-if="errors.name" class="invalid-feedback">
            {{ errors.name }}
          </div>
        </div>

        <!-- Surname field -->
        <div class="mb-3">
          <label for="surname" class="form-label">Surname</label>
          <input
            type="text"
            class="form-control"
            id="surname"
            v-model="form.surname"
            :class="{ 'is-invalid': errors.surname }"
            required
          >
          <div v-if="errors.surname" class="invalid-feedback">
            {{ errors.surname }}
          </div>
        </div>

        <!-- Submit button -->
        <div class="mb-3">
          <button type="submit" class="btn btn-primary" :disabled="submitting">
            <span v-if="submitting" class="spinner-border spinner-border-sm me-2" role="status"></span>
            Update Author
          </button>
          <router-link :to="`/authors/${author.id}`" class="btn btn-secondary ms-2">Cancel</router-link>
        </div>
      </form>
    </div>
    <div v-else class="alert alert-warning">
      Author not found.
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthorStore } from '@/stores/authorStore';

export default {
  name: 'AuthorEdit',
  emits: ['flash'],

  setup(props, { emit }) {
    const route = useRoute();
    const router = useRouter();
    const authorStore = useAuthorStore();

    const form = ref({
      name: '',
      surname: ''
    });

    const errors = ref({});
    const submitting = ref(false);

    const author = computed(() => authorStore.author);
    const loading = computed(() => authorStore.loading);
    const error = computed(() => authorStore.error);

    const fetchAuthor = async () => {
      try {
        await authorStore.fetchAuthor(route.params.id);

        // Initialize form with author data
        if (authorStore.author) {
          form.value = {
            name: authorStore.author.name,
            surname: authorStore.author.surname
          };
        }
      } catch (error) {
        console.error('Error fetching author:', error);
      }
    };

    const submitForm = async () => {
      errors.value = {};
      submitting.value = true;

      try {
        await authorStore.updateAuthor(author.value.id, form.value);
        emit('flash', 'Author updated successfully.');
        router.push(`/authors/${author.value.id}`);
      } catch (error) {
        console.error('Error updating author:', error);

        if (error.response?.status === 422) {
          // Validation errors
          errors.value = error.response.data.errors;
          emit('flash', 'Please correct the errors in the form.', 'danger');
        } else {
          emit('flash', 'Failed to update author.', 'danger');
        }
      } finally {
        submitting.value = false;
      }
    };

    onMounted(fetchAuthor);

    return {
      author,
      form,
      errors,
      loading,
      error,
      submitting,
      submitForm
    };
  }
}
</script>
