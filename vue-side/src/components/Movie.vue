<template>
  <section class="movies-sec">
    <div class="include-rated-movies">
      <input type="checkbox" id="include-rated-movies-input" v-model="includeRatedMovies" />
      <label for="include-rated-movies-input" class="include-rated-movies-label"
        >include rated movies</label
      >
    </div>

    <button class="sync-btn" @click="onSyncClick">Sync with IMDB</button>

    <section class="ag-grid-sec">
      <ag-grid-vue style="width: 100%; height: 100%" :rowData="rowData" :columnDefs="colDefs">
      </ag-grid-vue>
    </section>
  </section>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      movies: [],

      rowData: [],
      colDefs: [],

      includeRatedMovies: false,
      ratedMovies: [],
    }
  },

  methods: {
    getAllMovies() {
      axios
        .get('http://127.0.0.1:8000/api/movie/getAll')
        .then(({ data }) => {
          this.movies = data
          this.setRowData()
        })
        .catch(() => {})
    },

    setRowData() {
      this.rowData = this.movies
        .filter((movie) => this.includeRatedMovies || !this.ratedMovies.includes(movie.IMDB_id))
        .map((movie) => {
          return {
            IMDB_id: movie.IMDB_id,
            title: movie.title,
            type: movie.type,
            primaryImageUrl: movie.primary_image_url,
            releaseYear: movie.release_year,
            endYear: movie.end_year,
            aggregateRating: movie.aggregate_rating,
            voteCount: movie.vote_count,
            myRating: Math.round(movie.vote_count * movie.aggregate_rating),
          }
        })
    },

    setColDefs() {
      this.colDefs = [
        { field: 'IMDB_id' },
        { field: 'title' },
        { field: 'type' },
        { field: 'primaryImageUrl' },
        { field: 'releaseYear' },
        { field: 'endYear' },
        { field: 'aggregateRating' },
        { field: 'voteCount' },
        { field: 'myRating' },
      ]
    },

    onSyncClick() {
      axios
        .post(
          'https://caching.graphql.imdb.com/',
          {
            operationName: 'AdvancedTitleSearch',
            variables: {
              explicitContentConstraint: { explicitContentFilter: 'INCLUDE_ADULT' },
              first: 1000,
              locale: 'en-US',
              sortBy: 'USER_RATING_COUNT',
              sortOrder: 'DESC',
              userRatingsConstraint: { aggregateRatingRange: { max: 10, min: 6.9 } },
            },
            extensions: {
              persistedQuery: {
                sha256Hash: '6842af47c3f1c43431ae23d394f3aa05ab840146b146a2666d4aa0dc346dc482',
                version: 1,
              },
            },
          },
          {
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
          },
        )
        .then(({ data }) => {
          let IMDB_movies = data.data.advancedTitleSearch.edges.map((edge) => {
            return {
              IMDB_id: edge.node.title.id,
              title: edge.node.title.titleText.text,
              type: edge.node.title.titleType.text,
              primary_image_url: edge.node.title.primaryImage.url,
              release_year: edge.node.title.releaseYear.year,
              end_year: edge.node.title.releaseYear.endYear,
              aggregate_rating: edge.node.title.ratingsSummary.aggregateRating,
              vote_count: edge.node.title.ratingsSummary.voteCount,
            }
          })

          this.syncWithIMDB(IMDB_movies)
        })
        .catch((error) => {
          console.error('Error:', error)
        })
    },

    syncWithIMDB(IMDB_movies) {
      axios
        .post('http://127.0.0.1:8000/api/movie/syncWithIMDB', {
          IMDB_movies: IMDB_movies,
        })
        .then(() => {
          this.getAllMovies()
        })
        .catch(() => {})
    },

    getRatedMovies() {
      const url = 'https://api.graphql.imdb.com/'

      const payload = {
        operationName: 'RatingsPage',
        variables: {
          filter: {
            explicitContentConstraint: { explicitContentFilter: 'INCLUDE_ADULT' },
            singleUserRatingConstraint: { filterType: 'INCLUDE', userId: 'ur115987309' },
          },
          first: 250,
          locale: 'en-US',
          sort: { sortBy: 'SINGLE_USER_RATING', sortOrder: 'ASC' },
        },
        extensions: {
          persistedQuery: {
            sha256Hash: '90e077634ebba07351579909fe00a18052873cdd278133cb5f0bc2a6efd25a6d',
            version: 1,
          },
        },
      }

      axios
        .post(url, payload, {
          headers: {
            'Content-Type': 'application/json',
          },
        })
        .then((response) => {
          this.ratedMovies = response.data.data.advancedTitleSearch.edges.map(
            (edge) => edge.node.title.id,
          )

          this.getAllMovies()
        })
        .catch(() => {})
    },
  },

  created() {
    this.setColDefs()
    this.getRatedMovies()
  },

  watch: {
    includeRatedMovies() {
      this.setRowData()
    },
  },
}
</script>

<style scoped>
.movies-sec {
  box-sizing: border-box;

  height: 100vh;

  padding: 1em;

  display: grid;
  grid-template-columns: auto;
  grid-template-rows: auto auto 1fr;

  row-gap: 1em;
}

.ag-grid-sec {
  height: 100%;
}

.sync-btn {
  padding: 0.5em;

  justify-self: start;

  cursor: pointer;
}

.include-rated-movies {
  display: flex;
  align-items: center;
}

#include-rated-movies-input {
  width: 1em;
  height: 1em;
}

.include-rated-movies-label {
  margin-left: 0.3em;
}
</style>
