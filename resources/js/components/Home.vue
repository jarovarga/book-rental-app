<template>
  <div class="home">
    <div class="jumbotron">
      <h1 class="display-4">Welcome to Book Rental App</h1>
      <p class="lead">A simple application to manage books and authors.</p>
      <hr class="my-4">
      <p>Use the navigation above to browse books and authors.</p>
      <div class="d-flex gap-2">
        <router-link to="/books" class="btn btn-primary">Browse Books</router-link>
        <router-link to="/authors" class="btn btn-secondary">Browse Authors</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home'
}
</script>

<style scoped>
.home {
  padding: 2rem 0;
}
.jumbotron {
  padding: 2rem 1rem;
  margin-bottom: 2rem;
  background-color: #e9ecef;
  border-radius: 0.3rem;
}
</style>
