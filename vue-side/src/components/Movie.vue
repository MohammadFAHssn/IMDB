<template>
  <div>!</div>
</template>

<script>
export default {
  methods: {
    getAllMovies() {
      this.$axios
        .get('movie/getAll')
        .then(() => {})
        .catch(() => {})
    },
  },

  created() {
    this.getAllMovies()
  },
}
</script>
