<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Event Calendar</div>
          <div class="card-body">
            <div class="row">
              <div class="cold-md-12 col-lg-12">
                <calendar></calendar>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import calendar from "../components/CalendarComponent.vue";

  export default {
    mounted() {
      console.log('Component mounted.')
    },

    components: {
      calendar
    }
  }
</script>
