let mutations = {
  GET_EVENTS(state, events) {
    state.eventList = events;
  },
  SET_CALENDAR(state, date) {
    state.calendarDate = date;
  },
  SET_DATES_ARR(state, datesArr) {
    state.datesArr = datesArr;
  }
}
export default mutations;