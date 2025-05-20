<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Create New Author</h1>
    </div>

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
          Create Author
        </button>
        <router-link to="/authors" class="btn btn-secondary ms-2">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthorStore } from '@/stores/authorStore';

export default {
  name: 'AuthorCreate',
  emits: ['flash'],

  setup(props, { emit }) {
    const router = useRouter();
    const authorStore = useAuthorStore();

    const form = ref({
      name: '',
      surname: ''
    });

    const errors = ref({});
    const submitting = ref(false);

    const submitForm = async () => {
      errors.value = {};
      submitting.value = true;

      try {
        const author = await authorStore.createAuthor(form.value);
        emit('flash', 'Author created successfully.');
        router.push(`/authors/${author.id}`);
      } catch (error) {
        console.error('Error creating author:', error);

        if (error.response?.status === 422) {
          // Validation errors
          errors.value = error.response.data.errors;
          emit('flash', 'Please correct the errors in the form.', 'danger');
        } else {
          emit('flash', 'Failed to create author.', 'danger');
        }
      } finally {
        submitting.value = false;
      }
    };

    return {
      form,
      errors,
      submitting,
      submitForm
    };
  }
}
</script>
