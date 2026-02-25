(function () {
    class AESearch {
        options = {
            wrapperClass: 'aesearch',
            resultsClass: 'aesearch-results',
            inputName: 'search',
            url: '/aesearch',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            minSearch: 3,
        };

        constructor(options = {}) {
            if (typeof options !== 'object') throw new Error('Wrong options');

            if (typeof options.headers === 'object') {
                Object.assign(this.options.headers, options.headers);
            }
            delete options.headers;

            Object.assign(this.options, options);

            this.init();
        }

        init() {
            const wrappers = Array.from(document.getElementsByClassName(this.options.wrapperClass));
            wrappers.forEach(wrapper => {
                const input = wrapper.querySelector('input[name="' + this.options.inputName + '"]');
                if (input) {
                    input.addEventListener('input', this.debounce(this.submit), false);
                    input.addEventListener('focus', (e) => {
                        let results = wrapper.getElementsByClassName(this.options.resultsClass)[0];
                        if(results.innerHTML !== '' && e.target.value !== '') {
                            results.classList.remove('hidden');
                        }
                    }, false);
                }
                const el = document.createElement('div');
                el.classList.add('aesearch-results', 'hidden');
                wrapper.appendChild(el);
                document.addEventListener('click', (e) => {
                    if (!wrapper.contains(e.target)) el.classList.add('hidden');
                });
            });
        };

        debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                window.clearTimeout(timer);
                timer = window.setTimeout(() => {
                    func.apply(this, args);
                }, timeout);
            };
        }

        submit(e) {
            const input = e.target;
            const wrapper = input.closest('.' + this.options.wrapperClass);
            const results = wrapper.getElementsByClassName(this.options.resultsClass)[0];
            const value = input.value;
            if(value.length < this.options.minSearch) {
                results.classList.add('hidden');
                return;
            }
            this.request({search: value}, (response) => {
                if (response.status) {
                    results.innerHTML = response.results;
                    results.classList.remove('hidden');
                } else {
                    results.classList.add('hidden');
                }
            }, (error) => {
                console.log(error)
                results.classList.add('hidden');
            });
        };

        request(data, successCallback, errorCallback) {
            fetch(new Request(this.options.url, {
                method: 'post',
                credentials: 'same-origin',
                headers: Object.assign(this.options.headers, {
                    Accept: 'application/json',
                    'Content-type': 'application/json'
                }),
                body: JSON.stringify(data)
            }))
                .then(response => {
                    if (!response.ok) {
                        throw new Error();
                    }
                    return response.json();
                })
                .then(successCallback)
                .catch(errorCallback);
        }
        ;

    }

    window.AESearch = AESearch;
})
();
