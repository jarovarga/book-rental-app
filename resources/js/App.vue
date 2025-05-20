<template>
  <div class="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
      <div class="container">
        <router-link class="navbar-brand" to="/">Book Rental App</router-link>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <router-link class="nav-link" :class="{ active: $route.name && $route.name.startsWith('authors') }" to="/authors">Authors</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" :class="{ active: $route.name && $route.name.startsWith('books') }" to="/books">Books</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container">
      <!-- Flash messages -->
      <div v-if="flashMessage" :class="['alert', `alert-${flashMessage.type}`, 'mb-4']">
        {{ flashMessage.text }}
      </div>

      <!-- Main content -->
      <router-view @flash="setFlashMessage"></router-view>
    </main>
  </div>
</template>

<script>
export default {
  data() {
    return {
      flashMessage: null,
      flashTimeout: null
    };
  },
  methods: {
    setFlashMessage(message, type = 'success', duration = 5000) {
      // Clear any existing timeout
      if (this.flashTimeout) {
        clearTimeout(this.flashTimeout);
      }

      // Set the flash message
      this.flashMessage = {
        text: message,
        type: type
      };

      // Set a timeout to clear the message
      this.flashTimeout = setTimeout(() => {
        this.flashMessage = null;
      }, duration);
    }
  }
};
</script>
