$(function () {
    const order = new Vue({
        el: '#track-order',
        data: {
            track_form: true,
            phone_number: '',
            email: '',
            orders: [],
        },
        methods: {
            tracking: function () {
                let hasError = !this.phone_number
                    || !this.email
                    || this.errors.has('email')
                    || this.errors.has('phone number');
                if (hasError) {
                    Snackbar.pushMessage('You must fill email and phone number');
                } else {
                    this.fetchOrder();
                }
            },
            continueTrack: function () {
                this.track_form = true;
            },
            fetchOrder() {
                axios.post('/order/track', {
                    phone_number: this.phone_number,
                    email: this.email,
                })
                    .then(res => {
                        console.log(res.data);
                        this.orders = res.data;
                        this.track_form = false;
                    })
                    .catch(err => {

                    });
            },
        },
    });
});
