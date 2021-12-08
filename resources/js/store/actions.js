let actions = {
  GET_EVENTS({commit}, params) {
    axios.get('/api/events', params).then((response) => {
      commit('GET_EVENTS', response.data);
      // self.datesArr = this.createArrOfDates();
    }).catch( error => {
      console.log(error);
    });
  }
}

export default actions;