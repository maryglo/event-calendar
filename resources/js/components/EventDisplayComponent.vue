<template>
  <div class="d-flex align-items-center">
    <h4 class="mb-0 mr-1" v-for="(event, index) in eventList" v-if="event.days[dayOnly] && (isoDate >= stripUTC(event.start_date) && isoDate <= stripUTC(event.end_date))">
      <span class="badge badge-primary">{{ event.event_name }}</span>
    </h4>
  </div>
</template>

<script>
  export default {
    props: ['events', 'day'],

    mounted() {},

    computed: {
      dayOnly() {
        return this.day.toLocaleString("default", { weekday: "short" }).toLowerCase();
      },

      isoDate() {
        const offset = this.day.getTimezoneOffset();
        return this.stripUTC(new Date(this.day.getTime() - (offset*60*1000)).toISOString());
      },

      eventList() {
        return this.events;
      }
    },

    methods: {
      stripUTC(date) {
        return date.split('T')[0];
      }
    }
  }
</script>