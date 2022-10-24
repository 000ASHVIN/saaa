Vue.component("enroll", {
	props: {
		user: {
			required: true,
		},

		course: {
			required: true,
		},

		donations: {
			required: false,
			default: false,
		},
	},

	data: function() {
		return {
			option: null,
			course_total:0,
			course_type: "full",
			id_number: null,
			address: {
				street_name: "",
				building: "",
				suburb: "",
				city: "",
				province: "",
				country: "",
				postal_code: "",
				errors: [],
			},
			instantlink: "",
			payment_token: "",
			instant_eft_success: false,
			couponApplied: false,
			forms: {
				enroll: $.extend(
					true,
					new AppForm({
						busy: false,
						paymentOption: null,
						card: null,
						donations: 1,
						terms: false,
					}),
					App.forms.enroll
				),
				addingNewCard: false,
			},
			newcard: {
				number: "",
				holder: "",
				exp_month: "",
				exp_year: "",
				cvv: "",
				return: "",
				errors: [],
			},
			threeDs: {
				url: "",
				connector: "",
				MD: "",
				TermUrl: "",
				PaReq: "",
			},
			readyForThreeDs: false,
			dataLayer: [],
		};
	},

	ready: function() {
		this.course.semester_price = JSON.parse(this.course.semester_price)
		// console.log(this.course.semester_price)
		window.addEventListener("message", (event) => {
			if (typeof event.data == "string") {
				try {
					eval(event.data);
					setTimeout(() => {
						this.forms.enroll.busy = false;
						swal.close();
					}, 2000);
				} catch (e) {
					this.forms.enroll.busy = false;
					// swal.close()
				}
			}
		});
	},

	methods: {
		popEft() {
			this.process();
			this.forms.enroll.busy = true;
			this.fieldValidate();
			// this.getEftLink();
		},
		fieldValidate()
		{
			var payload = {
				id_number: this.id_number,
				street_name: this.address.street_name,
				building: this.address.building,
				suburb: this.address.suburb,
				city: this.address.city,
				province: this.address.province,
				country: this.address.country,
				postal_code: this.address.postal_code,
			};
			this.processPayment();

			this.$http
				.post(
					"checkout/validate",
					payload
				)
				.success(function(response) {
                    this.forms.enroll.busy = false;
                    swal.close();
                    this.getEftLink();
				})
				.error(function(errors) {
					this.address.errors = errors;
					this.forms.enroll.busy = false;
					swal.close();
					this.forms.enroll.errors.set(errors);
				});
		},
		coursetotalCalculate()
		{
			if(this.option =='monthly')
			{
				this.course_total = this.course.monthly_enrollment_fee
			}
			if(this.option =='yearly')
			{
				this.course_total = this.course.annual_discounted_price
				if(this.course.type_of_course == 'semester'){
					if(this.course_type == 'partially')
					{
						this.course_total = this.course.semester_price
					}
					if(this.course_type == 'full')
					{
						this.course_total = this.course.semester_price * this.course.no_of_semesters
					}
				}
			}
		},
		addCard() {
			this.newcard.return = `/courses/enroll/${
				this.course.reference
			}?threeDs=yes`;

			this.processPayment();

			this.$http
				.post("/account/billing", this.newcard)
				.then((response) => {
					if (
						response.data.redirect === undefined ||
						response.data.redirect === null
					) {
						this.saveCard(response.data.id);
						this.addingNewCard = false;
					} else {
						this.threeDs.url = response.data.redirect.url;
						for (
							var i = response.data.redirect.parameters.length - 1;
							i >= 0;
							i--
						) {
							this.threeDs[response.data.redirect.parameters[i].name] =
								response.data.redirect.parameters[i].value;
						}
						this.readyForThreeDs = true;
						swal.close();
					}
				})
				.catch((errors) => {
					this.newcard.errors = errors.data;
					swal.close();
				});
		},

		saveCard(id) {
			this.$http
				.post("/account/billing/card", { id: id })
				.then((response) => {
					this.user.cards.push(response.data.card);
					this.forms.enroll.card = response.data.card.id;
					swal.close();
				})
				.catch((errors) => {
					if (errors.number) {
						swal({
							type: "error",
							title: "Whoops",
							text: errors.number,
						});
					}
				});
		},
		applyCouponCode: function() {
			let $cid = $("input[name=course_id]").val();
			let data = this.serializeData($("#Coupon_code_apply").serializeArray());

			this.$http.post("/check/" + $cid, data).success((response) => {
				if (response.status == 1) {
					this.couponApplied = true;
					$courseObj = JSON.parse(response.object);
					this.course.annual_discounted_price =
						$courseObj.annual_discounted_price;
				} else {
					swal({
						type: "error",
						title: "Error",
						text: response.message,
					});
				}
			});
		},
		serializeData: function(a) {
			var o = {};

			$.each(a, function() {
				if (o[this.name]) {
					if (!o[this.name].push) {
						o[this.name] = [o[this.name]];
					}
					o[this.name].push(this.value || "");
				} else {
					o[this.name] = this.value || "";
				}
			});
			return o;
		},
		cancelPayment: function() {
			this.forms.enroll.busy = false;
		},

		getEftLink: function() {
			this.processPayment();

			let data = {
				course: this.course.id,
				option: this.option,
				course_type: this.course_type,
				donation_amount: this.getDonations(),
			};

			this.$http.post("/instant-eft", data).success((response) => {
				this.instantlink = response.link;
				this.payment_token = response.key;
				this.processEft();
			});
		},

		startListener: function() {
			var pusher = new Pusher("5e9e56a5a0ebaf5484b0", {
				cluster: "mt1",
			});

			var channel = pusher.subscribe("instantefts");

			channel.bind("App\\Events\\InstantEftNotificationReceived", (data) => {
				if (this.payment_token == data.payment_key) {
					if (data.success === true) {
						this.process();
						this.instant_eft_success = true;
						this.sendRegistration();
					} else {
						this.cancelPayment();
						swal({
							type: "error",
							title: "Error",
							text: "Your Payment has failed, please try again",
						});
					}
				}
			});
		},

		processEft: function() {
			var paymentKey = this.payment_token;
			var paymentType = "eft";

			eftSec.checkout.settings.serviceUrl =
				"{protocol}://eft.ppay.io/rpp-transaction/create-from-key";
			eftSec.checkout.settings.checkoutRedirect = false;
			eftSec.checkout.settings.onComplete = (data) => {
				eftSec.checkout.hideFrame();
				this.process();

				if (data.success) {
					this.instant_eft_success = true;
					this.sendRegistration();
				} else {
					swal({
						type: "error",
						title: "Error",
						text: "Your Payment has failed, please try again",
					});
				}
			};
			eftSec.checkout.init({
				paymentKey: paymentKey,
				paymentType: paymentType,
				onLoad: function() {
					swal.close();
				},
			});
		},

		process: function() {
			swal({
				title: "",
				text:
					'<i id="busy" class="fa fa-spinner fa-pulse" style="font-size: 8em; color: #ffffff;"></i>',
				html: true,
				allowEscapeKey: false,
				allowOutsideClick: false,
				showCancelButton: false,
				showConfirmButton: false,
				closeOnCancel: false,
				customClass: "no-bg",
			});
		},
		processPayment: function() {
			swal({
				type: "warning",
				title: "Payment Processing",
				text:
					"Please wait while we are processing your payment.Do not Refresh page or close browser",
			});
		},
		sendTransactions: function(invoice) {
			this.dataLayer.event = "checkout.event";
			this.dataLayer.transactionId = invoice.reference;
			this.dataLayer.transactionTotal = parseFloat(
				parseFloat(invoice.total).toFixed(2)
			);
			this.dataLayer.transactionTax = parseFloat(
				((invoice.total / 100) * invoice.vat_rate).toFixed(2)
			);
			this.dataLayer.transactionProducts = [];

			if (invoice.items.length > 0) {
				const self = this;
				invoice.items.forEach(function(item) {
					var prof = {
						sku: "course-" + item.item_id,
						name: item.name,
						category: "Course",
						price: item.price,
						quantity: item.quantity,
					};
					self.dataLayer.transactionProducts.push(prof);
				});
			}
			this.dataLayer = Object.assign({}, this.dataLayer);
			window.dataLayer.push(this.dataLayer);
		},
		register: function() {
			this.forms.enroll.errors.forget();
			this.forms.enroll.busy = true;
			// this.process();
			this.sendRegistration();
		},

		sendRegistration: function() {
			if (
				this.forms.enroll.paymentOption == "eft" &&
				this.instant_eft_success == false
			) {
				this.forms.enroll.busy = true;
				return;
			}

			if (this.forms.enroll.paymentOption == "cc" && !this.forms.enroll.card) {
				swal({
					type: "warning",
					title: "Error",
					text:
						"Unable to purchase without credit card, please select credit card",
				});

				this.forms.enroll.busy = false;
				return;
			}

			var payload = {
				course: this.course.id,
				course_type: this.course_type,
				terms: this.forms.enroll.terms,
				card: this.forms.enroll.card,
				paymentOption: this.forms.enroll.paymentOption,
				enrollmentOption: this.option,
				donations: this.getDonations(),
				id_number: this.id_number,
				street_name: this.address.street_name,
				building: this.address.building,
				suburb: this.address.suburb,
				city: this.address.city,
				province: this.address.province,
				country: this.address.country,
				postal_code: this.address.postal_code,
			};
			this.processPayment();

			this.$http
				.post(
					"/courses/checkout/" + this.course.reference + "/complete",
					payload
				)
				.success(function(response) {
					this.forms.enroll.busy = false;
					swal.close();
					if (response.invoice !== undefined) {
						// this.sendTransactions(response.invoice);
					}
					 window.location = "/dashboard/invoices";
				})
				.error(function(errors) {
					this.address.errors = errors;
					this.forms.enroll.busy = false;
					swal.close();
					this.forms.enroll.errors.set(errors);
				});
		},

		/*
		 * Calculate the donation
		 */
		getDonations: function() {
			var total = 0;
			if (this.forms.enroll.donations && this.option == "yearly") {
				total = this.donations;
			}
			return total;
		},
	},
});
