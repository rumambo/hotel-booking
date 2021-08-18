<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ message.check_booking }}</h5>
            <button type="button" class="close" @click="$emit('close')" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="checkBooking">
            <form class="row"  @submit.prevent="submit">
              <div class="col-md-4">
                <input type="text" v-model="fields.tel" class="form-control" :placeholder="message.tel" required/>
              </div>
              <div class="col-md-4">
                <input type="text" v-model="fields.order_id" class="form-control" :placeholder="message.order_id" required/>
              </div>
              <div class="col-md-4">
                <button class="btn btn-primary btn-block" type="submit">{{ message.check }}</button>
              </div>
            </form>
            <div id="check_result"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<!--<style lang="scss" src="./component_name.scss"></style>-->

<script>
module.exports = {
  data() {
    return {
      fields: {},
      errors: {},
    }
  },
  methods: {
    submit() {
      // this.errors = {};
      axios.post(ajaxurl + '?action=hb_check', this.fields).then(response => {
        document.getElementById('check_result').innerHTML = '<div class="mt-3">' + response.data + '</div>';
        // console.log(response.data);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors || {};
        }
      });
    },
  }
}
</script>
