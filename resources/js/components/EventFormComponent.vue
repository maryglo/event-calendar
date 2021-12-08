<template>
  <form
    id="eventForm"
    @submit="checkEventForm"
    method="post"
  >
  <div class="form-group">
    <label for="inputEventName">Event</label>
    <input type="text" class="form-control" id="inputEventName" placeholder="" v-model="newEvent.event_name" required>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label>From</label>
      <datepicker name="fromDate" input-class="form-control" v-model="newEvent.start_date" required></datepicker>
    </div>
    <div class="form-group col-md-6">
      <label>To</label>
      <datepicker name="toDate" input-class="form-control" v-model="newEvent.end_date"></datepicker>
    </div>
  </div>
  <div class="form-group">
    <div class="form-check form-check-inline" v-for="(day, index) in days">
      <input class="form-check-input" :id="day" :value="day" type="checkbox" v-model="newEvent.selected_days">
      <label class="form-check-label">
        {{ day }}
      </label>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Save</button>
    </form>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import { mapGetters } from "vuex";

  export default {
    mounted() {
      console.log('Component mounted.')
    },

    components: {
      Datepicker
    },

    data() {
      return {
        days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        errors: []
      }
    },
    methods: {
      checkEventForm: function(e) {
        e.preventDefault();

        if (this.newEvent.event_name && this.newEvent.start_date
          && this.newEvent.end_date && this.newEvent.selected_days.length > 0) {
          this.submitEvent();
        }
      },

      submitEvent() {
        axios.post('/api/events', this.newEvent).then(res => {
          this.$toaster.success(res.data.message);
          this.$emit('submit', this.newEvent.start_date);
        }).catch(err => {
          console.log(err);
          this.$toaster.success(err.data.message);
        });
      }
    },

    computed: {
      ...mapGetters(["newEvent"])
    },

    emits: ['submit']
  }
</script>