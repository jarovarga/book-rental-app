import { defineStore } from 'pinia';
import axios from 'axios';

export const useBookStore = defineStore('book', {
  state: () => ({
    books: [],
    book: null,
    authors: [],
    loading: false,
    error: null
  }),

  actions: {
    async fetchBooks(filters = {}) {
      this.loading = true;
      try {
        // Convert filters to query string
        const queryParams = new URLSearchParams();
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== null && value !== undefined && value !== '') {
            queryParams.append(key, value);
          }
        });

        const response = await axios.get(`/api/books?${queryParams.toString()}`);
        this.books = response.data.data;
        return this.books;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch books';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchBook(id) {
      this.loading = true;
      try {
        const response = await axios.get(`/api/books/${id}`);
        this.book = response.data.data;
        return this.book;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch book';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createBook(bookData) {
      this.loading = true;
      try {
        const response = await axios.post('/api/books', bookData);
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to create book';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateBook(id, bookData) {
      this.loading = true;
      try {
        const response = await axios.put(`/api/books/${id}`, bookData);
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update book';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async deleteBook(id) {
      this.loading = true;
      try {
        await axios.delete(`/api/books/${id}`);
        // Remove the book from the books array
        this.books = this.books.filter(book => book.id !== id);
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete book';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async toggleBorrowed(id) {
      this.loading = true;
      try {
        const response = await axios.patch(`/api/books/${id}/toggle-borrowed`);
        // Update the book in the books array
        const index = this.books.findIndex(book => book.id === id);
        if (index !== -1) {
          this.books[index] = response.data.data;
        }
        // Update the book property if it's the currently viewed book
        if (this.book && this.book.id === id) {
          this.book = response.data.data;
        }
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to toggle borrowed status';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchAuthors() {
      try {
        const response = await axios.get('/api/authors');
        this.authors = response.data.data;
        return this.authors;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch authors';
        throw error;
      }
    }
  }
});
