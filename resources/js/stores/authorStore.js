import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthorStore = defineStore('author', {
  state: () => ({
    authors: [],
    author: null,
    loading: false,
    error: null
  }),

  actions: {
    async fetchAuthors(filters = {}) {
      this.loading = true;
      try {
        // Convert filters to query string
        const queryParams = new URLSearchParams();
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== null && value !== undefined && value !== '') {
            queryParams.append(key, value);
          }
        });

        const response = await axios.get(`/api/authors?${queryParams.toString()}`);
        this.authors = response.data.data;
        return this.authors;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch authors';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchAuthor(id) {
      this.loading = true;
      try {
        const response = await axios.get(`/api/authors/${id}`);
        this.author = response.data.data;
        return this.author;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch author';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createAuthor(authorData) {
      this.loading = true;
      try {
        const response = await axios.post('/api/authors', authorData);
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to create author';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateAuthor(id, authorData) {
      this.loading = true;
      try {
        const response = await axios.put(`/api/authors/${id}`, authorData);
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update author';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async deleteAuthor(id) {
      this.loading = true;
      try {
        await axios.delete(`/api/authors/${id}`);
        // Remove the author from the authors array
        this.authors = this.authors.filter(author => author.id !== id);
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete author';
        throw error;
      } finally {
        this.loading = false;
      }
    }
  }
});
