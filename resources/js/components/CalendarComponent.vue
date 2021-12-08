<template>
  <div class="row">
    <div class="col-md-5 col-lg-4">
      <event-form @submit="updateCalendar"></event-form>
    </div>
    <div class="cold-md-7 col-lg-8">
      <div class="row">
        <div class="col-sm-6 float-xl-right" style="height:130px;">
          <h5>Select Calendar</h5>
          <datepicker name="currentCalendar" :minimumView="'month'" :maximumView="'month'" v-model="calDate" @closed="displayCalendar"></datepicker>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h4>{{ calendarDate.toLocaleString('default', { month: 'long' }) }} {{ calendarDate.getFullYear() }}</h4>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-start align-items-center" v-for="(dateObj, index) in datesArr">
              <span class="date col col-md-3">{{ dateObj.getDate() }} {{ dateObj.toLocaleString("default", { weekday: "short" }) }}</span>
              <event-display v-bind:day="dateObj" v-bind:events="eventList"></event-display>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import { mapGetters } from "vuex";
  import eventDisplay from "../components/EventDisplayComponent.vue";
  import eventForm from "../components/EventFormComponent.vue";

  export default {
    mounted() {
      console.log('Component mounted.');
        this.displayCalendar();
    },

    components: {
      Datepicker,
      eventDisplay,
      eventForm
    },

    computed: {
      ...mapGetters(["eventList", "calendarDate", "datesArr"]),
      eventParams() {
        const params = new URLSearchParams();
        params.append('year', this.calendarDate.getFullYear());
        params.append('month', this.calendarDate.getMonth() + 1);
        return params;
      },
      calDate: {
        get() {
          return this.calendarDate;
        },
        set(value) {
          return this.$store.commit("SET_CALENDAR", value);
        }
      }
    },

    methods: {
      displayCalendar() {
        this.$store.commit("SET_DATES_ARR", this.createArrOfDates());
        this.$store.dispatch("GET_EVENTS", {params: this.eventParams});
      },

      createArrOfDates() {
        let arr = [];
        let start = new Date(this.calendarDate.getFullYear(), this.calendarDate.getMonth(), 1);
        const end = new Date(this.calendarDate.getFullYear(), this.calendarDate.getMonth() + 1, 0);

        while (start.getTime() <= end.getTime()) {
          arr.push(new Date(start));
          start.setDate(start.getDate() + 1);
        }

        return arr;
      },

      updateCalendar(value) {
        this.$store.commit("SET_CALENDAR", value);
        this.displayCalendar();
      }
    }
  }
</script>