let state = {
  newEvent: {
    event_name: '',
    start_date: '',
    end_date: '',
    selected_days: []
  },
  datesArr: [],
  eventList: [],
  calendarDate: new Date(new Date().getFullYear(), new Date().getMonth())
}
export default state;