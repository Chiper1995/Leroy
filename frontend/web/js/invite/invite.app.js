var app = new Vue({
    el: '#app',
    data: {
        messages: {
            serverError: '<strong>Ошибка!</strong> Что-то пошло не так, попробуйте позже',
            resultError: '<strong>Ошибка на странице!</strong> Проверьте ответы всех вопросов и попробуйте снова.'
        },
		started: false,
        sent: false,
        done: false,
        errorsExist: false,
        errorMessage: '',
		showRequiredMark: false,
        resultAnswers: {},
		questions: {},
        questionsData: [
            {
				id: 1,
                title: 'Укажите Ваш пол:',
                required: true,
                multi: false,
                answers: []
            },
            {
				id: 2,
                title: 'Сколько Вам полных лет:',
                required: true,
                multi: false,
                answers: [
                    {
                        title: '',
                        checkable: false,
						type: 'number',
						id: 'age'
                    }
                ]
            },
            {
				id: 3,
            	title: 'В каком городе Вы проживаете постоянно?',
				required: true,
				answers: [
					{
						title: 'Другое, укажите, что именно',
						checkable: true,
						type: 'text',
						id: 'other'
					}
				]
			},
			{
				id: 4,
                title: 'Как бы Вы могли описать Ваше семейное положение?',
                required: true,
                multi: false,
                answers: []
            },
            {
				id: 5,
                title: 'Есть ли у Вас дети, проживающие вместе с Вами? Если да, то сколько?',
                required: true,
                multi: false,
                answers: []
            },
			{
				id: 21,
				title: 'Пожалуйста, выберите вариант ответа, который наиболее точно описывает Ваше финансовое положение (информация будет использована исключительно в исследовательских целях):',
				required: true,
				multi: false,
				answers: []
			},
			{
				id: 15,
				title: 'Кто выполняет или будет выполнять работы по строительству / ремонту?',
				required: true,
				multi: false,
				answers: []
			},
			{
				id: 16,
				title: 'Кто принимает или будет принимать решения по выбору товаров для строительства и ремонта?',
				required: true,
				multi: false,
				answers: []
			},
			{
				id: 17,
				title: 'Кто покупает или будет покупать товары для строительства и ремонта?',
				required: true,
				multi: false,
				answers: []
			},
            {
				id: 6,
                title: 'Какое утверждение лучше всего описывает Вашу ситуацию в ремонте?',
                required: true,
                multi: false,
                answers: []
            },
            {
				id: 7,
                title: 'Когда Вы планируете завершить ремонт / строительство?',
                required: true,
                multi: false,
                answers: []
            },
			{
				id: 25,
				title: 'Какие работы Вы планируете выполнять в ходе вашего ремонта?',
				required: true,
				multi: true,
				answers: []
			},
            {
				id: 8,
                title: 'Какой объект Вы ремонтируете / строите или планируете ремонтировать / строить?',
                required: true,
                multi: true,
                answers: [
                    {
                        title: 'Другое, укажите, что именно',
                        checkable: true,
						type: 'text',
						id: 'other'
                    }
                ]
            },
            {
				id: 9,
                title: 'Скажите, есть ли у Вас дача?',
                required: true,
                multi: false,
                answers: []
            },
            {
				id: 10,
                title: 'Планируете ли Вы в этом году проводить какие-то работы на даче: посадка растений, строительство или обустройство (в т.ч. баня, беседка, зона отдыха, качели, бассейн и т.п.)',
                required: true,
                multi: false,
                answers: []
            },
			{
				id: 19,
				title: 'В каком магазине Леруа Мерлен Вам будет удобно получать вознаграждение за участие в проекте?',
				required: true,
				multi: false,
				answers: [
					{
						title: '',
						checkable: false,
						type: 'text',
						id: 'shop'
					}
				]
			},
			{
				id: 20,
				required: true,
            	title: 'Примерно на каком расстоянии от данного магазина Леруа Мерлен находится Ваш объект ремонта? В случае, если у Вас несколько объектов, ответьте про ближайший'
			},
			{
				id: 22,
				title: 'Укажите, пожалуйста, Ваши контактные данные:',
				hint: 'Нам необходимы Ваши контакты для того, чтобы мы могли с Вами связаться, рассказать про проект и заполнить недостающую информацию, а также ответить на вопросы.',
				multi: true,
				required: true,
				answers: [
					{
						title: 'Телефон для связи:',
						checkable: false,
						type: 'tel',
						pattern: '^[\-\+0-9]+$',
						labelClass: 'contacts-label',
						id: 'phone'
					},
					{
						title: 'Электронная почта:',
						type: 'email',
						checkable: false,
						labelClass: 'contacts-label',
						id: 'email'
					}
				]
			},
			{
				id: 23,
				title: 'Как к Вам обращаться при звонке:',
				required: true,
				multi: false,
				answers: [
					{
						title: '',
						checkable: false,
						type: 'text',
						id: 'fio'
					}
				]
			},
			{
				id: 24,
				title: 'Настоящим я подтверждаю, что я старше 18 лет и даю свое согласие на обработку всех указанных мною в \n' +
					'анкете данных, в том числе на их сбор, запись, систематизацию, накопление, хранение, уточнение (обновление, \n' +
					'изменение), использование, извлечение, удаление, уничтожение, в том числе с использованием технических средств \n' +
					'или без таковых ООО «ЛЕРУА МЕРЛЕН ВОСТОК» (ИНН: 5029069967, ОГРН 1035005516105, место нахождения: \n' +
					'141031, Московская обл., г. Мытищи, Осташковское шоссе, 1, тел. +7 (495) 961-01-60, далее – «Оператор»), а также \n' +
					'третьим лицам, осуществляющим обработку моих персональных данных по поручению Оператора, в соответствии с \n' +
					'законодательством Российской Федерации о персональных данных, а также на получение рекламных материалов, \n' +
					'информационных и новостных  рассылок. Я проинформирован, что обработка моих персональных данных \n' +
					'осуществляется Оператором в целях изучения потребностей покупателей, улучшения взаимодействия и качества \n' +
					'обслуживания покупателей, оказания связанных с этим услуг и осуществления процедур, предусмотренных \n' +
					'действующим законодательством РФ и внутренними нормами Оператора, не противоречащими действующему \n' +
					'законодательству РФ. Настоящее согласие дано мною лично и добровольно. Настоящее согласие действует в течение \n' +
					'10 лет и может быть отозвано мной в письменной форме в любой момент путем направления уведомления \n' +
					'Оператору и считается отозванным с момента получения Оператором указанного уведомления. ООО «ЛЕРУА \n' +
					'МЕРЛЕН ВОСТОК» вправе в любое время и без указания причин прекратить рассылку информации и рекламных \n' +
					'материалов о своих товарах, услугах, рекламных акциях и специальных предложениях.<br/>' +
					'Вся информация, опубликованная на платформе, является совместной интеллектуальной собственностью автора \n' +
					'информации и ООО «ЛЕРУА МЕРЛЕН ВОСТОК» и подлежит распространению третьими лицами на сторонних \n' +
					'ресурсах только в случае получения письменного согласия собственников.',
				required: true,
				multi: true,
				answers: [
					'Согласен'
				]
			}
        ]
    },
    computed: {
        visibleQuestions: function () {
            var vm = this;
            var result = {};

            for(var questionIndex in vm.questions) {
                if (vm.questions.hasOwnProperty(questionIndex)) {
                    if (vm.questions[questionIndex].visible) {
                        result[questionIndex] = vm.questions[questionIndex];
                    }
                }
            }

            return result;
        }
    },
    created: function () {
        var vm = this;

        // Prepare list of questions
		vm.questionsData.forEach(function(question, index) {
			vm.$set(vm.questions, index + 1, question);
		});

		//debugger;
        // Load answers texts
        axios.get('/invite/get-answers-texts')
            .then(function (response) {
                var lists = response.data;
                vm.$set(vm.getQuestionById(1), 'answers', lists.sex);
                vm.$set(vm.getQuestionById(3), 'answers', lists.city.concat(vm.getQuestionById(3).answers));
                vm.$set(vm.getQuestionById(4), 'answers', lists.family);
                vm.$set(vm.getQuestionById(5), 'answers', lists.children);
                vm.$set(vm.getQuestionById(6), 'answers', lists.repairStatus);
                vm.$set(vm.getQuestionById(7), 'answers', lists.repairWhenFinish);
                vm.$set(vm.getQuestionById(8), 'answers', lists.repairObject.concat(vm.getQuestionById(8).answers));
                vm.$set(vm.getQuestionById(9), 'answers', lists.haveCottage);
                vm.$set(vm.getQuestionById(10), 'answers', lists.planCottageWorks);
                vm.$set(vm.getQuestionById(15), 'answers', lists.whoWorker);
                vm.$set(vm.getQuestionById(16), 'answers', lists.whoChooser);
                vm.$set(vm.getQuestionById(17), 'answers', lists.whoBuyer);
				vm.$set(vm.getQuestionById(20), 'answers', lists.distance);
				vm.$set(vm.getQuestionById(21), 'answers', lists.money);
				vm.$set(vm.getQuestionById(25), 'answers', lists.typeOfRepair);

                vm.prepareResultAnswers();
            })
            .catch(function (error) {
                vm.showErrorMessage(vm.messages.serverError);
                console.log(error);
            });
    },
    watch: {
        resultAnswers: {
            handler: function (oldVal, newVal) {
                var vm = this;

                // Если чел < 19 лет, то скрываем все
				var age = parseInt(answerText(newVal, vm.getQuestionIndexById(2), 'age'));
                var greater19 = (age >= 19) && (age <= 65) && !answerIsEmpty(newVal, vm.getQuestionIndexById(1));
				for (var i = vm.getQuestionIndexById(2) + 1; i <= Object.keys(vm.questions).length; i++) {
					vm.questions[i].visible = greater19;
				}

				var repairEnded = (answerIs(newVal, vm.getQuestionIndexById(6), 5) || answerIs(newVal, vm.getQuestionIndexById(6), 6));
				for (i = vm.getQuestionIndexById(6) + 1; i <= Object.keys(vm.questions).length; i++) {
					vm.questions[i].visible = vm.questions[i].visible && !answerIsEmpty(newVal, vm.getQuestionIndexById(6)) && !repairEnded;
				}

				var repairAlmostEnded = answerIs(newVal, vm.getQuestionIndexById(7), 1);
				for (i = vm.getQuestionIndexById(7) + 1; i <= Object.keys(vm.questions).length; i++) {
					vm.questions[i].visible = vm.questions[i].visible && !answerIsEmpty(newVal, vm.getQuestionIndexById(7)) && !repairAlmostEnded;
				}

				var cabin = answerIs(newVal, vm.getQuestionIndexById(8), vm.getQuestionById(8).answers.find(function(e) {return e.value.toLowerCase() === 'дача'}).id);
				vm.getQuestionById(9).visible = vm.getQuestionById(9).visible && !answerIsEmpty(newVal, vm.getQuestionIndexById(8)) && !cabin;

				var notChooser = (answerIs(newVal, vm.getQuestionIndexById(16), 4) || answerIs(newVal, vm.getQuestionIndexById(16), 5));
				for (i = vm.getQuestionIndexById(16) + 1; i <= Object.keys(vm.questions).length; i++) {
					vm.questions[i].visible = vm.questions[i].visible && !answerIsEmpty(newVal, vm.getQuestionIndexById(16)) && !notChooser;
				}

				var notBuyer = (answerIs(newVal, vm.getQuestionIndexById(17), 4) || answerIs(newVal, vm.getQuestionIndexById(17), 5));
				for (i = vm.getQuestionIndexById(17) + 1; i <= Object.keys(vm.questions).length; i++) {
					vm.questions[i].visible = vm.questions[i].visible && !answerIsEmpty(newVal, vm.getQuestionIndexById(17)) && !notBuyer;
				}

				var dontHaveMoney = (answerIs(newVal, vm.getQuestionIndexById(21), 1) || answerIs(newVal, vm.getQuestionIndexById(21), 6));
				for (i = vm.getQuestionIndexById(21) + 1; i <= Object.keys(vm.questions).length; i++) {
					vm.questions[i].visible = vm.questions[i].visible && !answerIsEmpty(newVal, vm.getQuestionIndexById(21)) && !dontHaveMoney;
				}
            },
            deep: true
        }
    },
    methods: {
		start: function() {
			this.started = true;
		},
        submit: function() {
            var vm = this;

            if (!vm.validateData()) {
                return false;
            }

            // Don't need to send data if isn't finished
            if (!vm.questions[Object.keys(vm.questions).length].visible) {
				vm.sent = true;
				vm.done = false;
				return true;
			}

            // Send data to server
            var params = new URLSearchParams();
            params.append(vm.$el.getAttribute('csrf-token-name'), vm.$el.getAttribute('csrf-token'));
            params.append('data', JSON.stringify(vm.getDataForPost()));

            axios
                .post('/invite/save-invite', params)
                .then(function (response) {
                	if (response.data.result === 'error') {
						return Promise.reject(response.data.msg);
					}
					else {
						vm.prepareResultAnswers();
						vm.sent = true;
						vm.done = true;
					}
                })
                .catch(function (error) {
                    vm.showErrorMessage(vm.messages.serverError);
                    console.log(error);
                });
        },
        validateData: function() {
            var vm = this;
            var errorsExist = false;
            for(var questionIndex in vm.questions) {
                if (vm.questions.hasOwnProperty(questionIndex)) {
					if (vm.questions[questionIndex].visible) {
						vm.questions[questionIndex].error = false;

						if (vm.questions[questionIndex].required
							&& answerIsEmpty(vm.resultAnswers, questionIndex)
						) {
							vm.questions[questionIndex].error = true;
							vm.questions[questionIndex].errorMessage = 'Обязательный вопрос!';
							errorsExist = true;
						}
						else if (vm.questions[questionIndex].answers instanceof Array) {
							vm.questions[questionIndex].answers.forEach(function(answer) {
								if (answer instanceof Object) {
									answer.error = false;
									var value = answerText(vm.resultAnswers, questionIndex, answer.id);
									if (answer.required && (value === '')) {
										answer.error = true;
										answer.errorMessage = 'Обязательный вопрос!';
										errorsExist = true;
									}
									else if ((value !== '') && answer.type && (answer.type === 'email')) {
										var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
										if (!re.test(value)) {
											answer.error = true;
											answer.errorMessage = 'Email введен неверно!';
											errorsExist = true;
										}
									}
									else if ((value !== '') && answer.type && (answer.type === 'tel')) {
										var re = /^[+\-0-9]+$/;
										if (!re.test(value)) {
											answer.error = true;
											answer.errorMessage = 'Телефон введен неверно!';
											errorsExist = true;
										}
									}
								}
							});
						}
					}
					else {
						vm.questions[questionIndex].error = false;
					}
                }
            }

            if (errorsExist) {
                vm.showErrorMessage(vm.messages.resultError);
            }

            return !errorsExist;
        },
        showErrorMessage: function(message) {
            window.scrollTo(0, 0);
            var errorElement = document.getElementsByClassName("alert-danger");
            if (errorElement.length > 0) {
				errorElement[0].scrollIntoView();
			}

            this.errorsExist = true;
            this.errorMessage = message;
        },
        prepareResultAnswers: function() {
            var vm = this;

            for(var questionIndex in vm.questions) {
                if (vm.questions.hasOwnProperty(questionIndex)) {
                    vm.$set(vm.questions[questionIndex], 'visible', true);
                    vm.$set(vm.questions[questionIndex], 'error', false);

                    var texts = {};
                    if (vm.questions[questionIndex].answers.length === 0) {
                        texts[0] = '';
                    }
                    else {
                        vm.questions[questionIndex].answers.forEach(function (answer) {
                            if (typeof answer !== 'string') {
                                texts[answer.id] = '';
                            }
                        });
                    }

                    vm.$set(vm.resultAnswers, questionIndex, {value: (vm.questions[questionIndex].multi) ? [] : '', text: texts});
                }
            }
        },
        getDataForPost: function() {
            var vm = this;

            return {
                SessionID: vm.$el.getAttribute('csrf-token'),
				sex: vm.getAnswerData(vm.getQuestionIndexById(1)),
				age: vm.getAnswerText(vm.getQuestionIndexById(2), 'age'),
				city: vm.getAnswerData(vm.getQuestionIndexById(3)),
				cityOther: vm.getAnswerText(vm.getQuestionIndexById(3), 'other'),
				family: vm.getAnswerData(vm.getQuestionIndexById(4)),
				children: vm.getAnswerData(vm.getQuestionIndexById(5)),
				repairStatus: vm.getAnswerData(vm.getQuestionIndexById(6)),
				repairWhenFinish: vm.getAnswerData(vm.getQuestionIndexById(7)),
				repairObject: vm.getAnswerData(vm.getQuestionIndexById(8)),
				repairObjectOther: vm.getAnswerText(vm.getQuestionIndexById(8), 'other'),
				haveCottage: vm.getAnswerData(vm.getQuestionIndexById(9)),
				planCottageWorks: vm.getAnswerData(vm.getQuestionIndexById(10)),
				whoWorker: vm.getAnswerData(vm.getQuestionIndexById(15)),
				whoChooser: vm.getAnswerData(vm.getQuestionIndexById(16)),
				whoBuyer: vm.getAnswerData(vm.getQuestionIndexById(17)),
				shopName: vm.getAnswerText(vm.getQuestionIndexById(19), 'shop'),
				money: vm.getAnswerData(vm.getQuestionIndexById(21)),
				distance: vm.getAnswerData(vm.getQuestionIndexById(20)),
				phone: vm.getAnswerText(vm.getQuestionIndexById(22), 'phone'),
				email: vm.getAnswerText(vm.getQuestionIndexById(22), 'email'),
				fio: vm.getAnswerText(vm.getQuestionIndexById(23), 'fio'),
				typeOfRepair: vm.getAnswerData(vm.getQuestionIndexById(25))
            };
        },
        getAnswerData: function(questionIndex) {
            if (this.resultAnswers.hasOwnProperty(questionIndex)) {
                var resultAnswer = this.resultAnswers[questionIndex];

				if (resultAnswer.value instanceof Array) {
					return resultAnswer.value.join(';');
				}
				else {
					return resultAnswer.value;
				}
            }
        },
        getAnswerText: function(questionIndex, answerId) {
            if (this.resultAnswers.hasOwnProperty(questionIndex)) {
                var resultAnswer = this.resultAnswers[questionIndex];

                if (resultAnswer.text.hasOwnProperty(answerId)) {
                    return resultAnswer.text[answerId];
                }
                else {
                    return '';
                }
            }
        },
		getQuestionIndexById: function(id) {
			var vm = this;
			if (vm.__questionById === undefined) {
				vm.__questionById = {};
				for(var questionIndex in vm.questions) {
					if (vm.questions.hasOwnProperty(questionIndex)) {
						vm.__questionById[vm.questions[questionIndex].id] = questionIndex;
					}
				}
			}

			return parseInt(vm.__questionById[id]);
		},
		getQuestionById: function(id) {
			var vm = this;

			return vm.questions[vm.getQuestionIndexById(id)];
		}
    }
});

function answerIsEmpty(obj, questionIndex) {
    if (obj.hasOwnProperty(questionIndex)) {
        var value = obj[questionIndex].value;
        var isValueEmpty = (value instanceof Array)
            ? (value.length === 0)
            : String(value).length === 0;

        if (isValueEmpty) {
            var texts = obj[questionIndex].text;
            if (typeof texts === 'object') {
                var text = '';
                for(var textAnswerId in texts) {
                    if (texts.hasOwnProperty(textAnswerId)) {
                        text = text + texts[textAnswerId];
                    }
                }

                return text.length === 0;
            }
        }
        else {
            return false;
        }
    }
    else {
        return true;
    }
}

function answerIs(obj, questionIndex, answerValue) {
    if (obj.hasOwnProperty(questionIndex)) {
        var value = obj[questionIndex].value;
        if (value instanceof Array){
            return (value.length > 0) && (value.indexOf(answerValue) !== -1);
        }
        else {
            return (String(value) !== '') && (parseInt(value) === answerValue);
        }
    }
    else {
        return false;
    }
}

function answerText(obj, questionIndex, textAnswerId) {
	if (obj.hasOwnProperty(questionIndex)) {
		if (obj[questionIndex].hasOwnProperty('text')) {
			var texts = obj[questionIndex].text;
			if (typeof texts === 'object') {
				if (texts.hasOwnProperty(textAnswerId)) {
					return texts[textAnswerId];
				}
			}
		}
	}

	return '';
}