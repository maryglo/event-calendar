let getters = {
  newEvent: state => {
    return state.newEvent;
  },
  datesArr: state => {
    return state.datesArr;
  },
  eventList: state => {
    return state.eventList;
  },
  calendarDate: state => {
    return state.calendarDate;
  }
}

export default getters;