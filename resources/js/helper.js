var helper = {
    getSiteUrl: function (path = "") {
        return window.location.origin + path;
    },

    validateEmail: function (email) {
        var re =
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    },

    validateDomain: function (domain) {
        var re =
            /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/;
        return re.test(String(domain).toLowerCase());
    },

    alertError(msg) {
        $("#errorModalContent").html(msg);
        $("#errorModal").modal("show");
        $(".modal").css({ "overflow-y": "scroll" })

    },
    alertMsg(title = '', msg = '') {
        let modal = $("#messageModal");
        modal.find("#messageModelTitle").html(title);
        modal.find("#messageModelContent").html(msg);
        modal.modal("show");
        $(".modal").css({ "overflow-y": "scroll" });

    },

    confirm(msg, yesCallback, cancelCallback) {
        if (msg) {
            $("#confirmModalContent").html(msg);
        }

        $("#confirmModal").modal("show");

        $("#confirmBtnOk").on("click", () => {
            $("#confirmModal").modal("hide");

            yesCallback();
        });

        $("#confirmBtnCancel").on("click", () => {
            $("#confirmModal").modal("hide");

            cancelCallback();
        });
    },

    roundToHalf(num) {
        return Math.round(num * 2) / 2;
    },

    reloadPage() {
        window.location.reload();
    },

    serverErrorMessage() {
        return "An error occured, please try again or contact support if the problem persists.";
    },

    inchesToFeet(num) {
        return num / 12;
    },

    feetToInches(num) {
        return num * 12;
    },

    getDecimal(num) {
        return num % 1;
    },

    isMobilePhone() {
        return window.matchMedia("only screen and (max-width: 800px)").matches;
    },

    redirectTo(url) {
        window.location.href = url;
    },
    async validateForm(form) {
        if (form instanceof HTMLElement) {
            if (form.localName === "form") {
                let inputs = form.querySelectorAll("input");
                let errors = [];
                let loop = await inputs.forEach((input) => {
                    if (!("validation-except" in input.attributes)) {
                        //check all input types except FILE
                        if (!["file"].includes(input.type)) {
                            if (
                                "required" in input.attributes &&
                                !input.value.trim()
                            ) {
                                errors.push({ node: input, type: "required" });
                            }
                            if ("validation-match" in input.attributes) {
                                let matchElement =
                                    input.attributes["validation-match"].value;
                                matchElement = form[matchElement];
                                let match = matchElement.value;
                                let target = input.value;
                                if (match.trim() != target.trim()) {
                                    errors.push({ node: input, type: "match" });
                                    errors.push({
                                        node: matchElement,
                                        type: "match",
                                    });
                                }
                            }
                        }
                    }
                });
                //delete old messages
                document
                    .querySelectorAll(".invalid-feedback")
                    .forEach((e) => e.remove());
                // display errors
                errors.forEach((e) => {
                    let errorMsg = e.node.attributes[e.type + "-error-msg"];
                    let classes = e.node.attributes["error-class"]?.value ?? "";
                    // set error styel on input fields
                    e.node.classList.add("is-invalid");
                    if (errorMsg) {
                        let html = `<div class="invalid-feedback ${classes}">${errorMsg.value}</div>`;
                        e.node.insertAdjacentHTML("afterend", html);
                    }
                });
                if (!errors.length) {
                    form.querySelectorAll("input").forEach((e) => {
                        if (!("validation-except" in e.attributes)) {
                            e.classList.remove("is-invalid");
                            e.classList.add("is-valid");
                        }
                    });
                    document
                        .querySelectorAll(".invalid-feedback")
                        .forEach((e) => e.remove());
                }
                return {
                    ok: errors.length ? false : true,
                    errors,
                };
            } else {
                console.error(
                    `validateForm() acept  HTML element type of <form>. * YOU GIVE US  <${form.localName}>`.toUpperCase()
                );
                return false;
            }
        } else {
            console.error(`validateForm() acept  HTML element.`.toUpperCase());
            return false;
        }
    },
    loadImageOn(input, into) {
        input = $(input);
        if (input.length) {
            input.on("change", (e) => {
                into = $(into);
                if (into.length) {
                    let logo_image = e.target.files[0];
                    into.prop("src", URL.createObjectURL(logo_image));
                }
            });
        }
    },

    parseDate: function (dateInputVal) {
        let dateOnly = dateInputVal.slice(0, 10);
        dateOnly = dateOnly.replace(/\//g, '-');
        const parts = dateOnly.split("-");
        return new Date(parts[0], parts[1] - 1, parts[2]);
    },

    parseUSDate: function (dateInputVal) {
        let dateOnly = dateInputVal.slice(0, 10);
        dateOnly = dateOnly.replace(/\//g, '-');
        const parts = dateOnly.split("-");
        return new Date(parts[2], parts[0] - 1, parts[1]);
    },

    formatDate: function (dateString) {
        const date = this.parseDate(dateString);

        const options = { month: "numeric", day: "numeric", year: "numeric" };
        const formatted = new Intl.DateTimeFormat("en", options).format(date);

        return formatted;
    },
    formatDateCustom: function (dateString, options) {
        const date = this.parseDate(dateString);
        const formatted = new Intl.DateTimeFormat("en", options).format(date);

        return formatted;
    },
    create_id: function () {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) =>
            (
                c ^
                (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
            ).toString(16)
        );
    },
    parseZonePoints(points) {
        points = JSON.parse(points);
        return points.map((p) => {
            return {
                lat: parseFloat(p.lat),
                lng: parseFloat(p.lng),
            };
        });
    },
    orderOptions(target) {
        let options = $(target);
        let arr = options.map(function (_, o) { return { t: $(o).text(), v: o.value }; }).get();
        arr.sort(function (o1, o2) { return o1.t < o2.t ? 1 : o1.t > o2.t ? -1 : 0; });
        options.each(function (i, o) {
            o.value = arr[i].v;
            $(o).text(arr[i].t);
        });
    },
    cardNumberInput(target) {
        let ccNumberInput = document.querySelector(target),
            ccNumberPattern = /^\d{0,16}$/g,
            ccNumberSeparator = " ",
            ccNumberInputOldValue,
            ccNumberInputOldCursor,

            mask = (value, limit, separator) => {
                var output = [];
                for (let i = 0; i < value.length; i++) {
                    if (i !== 0 && i % limit === 0) {
                        output.push(separator);
                    }

                    output.push(value[i]);
                }

                return output.join("");
            },
            unmask = (value) => value.replace(/[^\d]/g, ''),
            checkSeparator = (position, interval) => Math.floor(position / (interval + 1)),
            ccNumberInputKeyDownHandler = (e) => {
                let el = e.target;
                ccNumberInputOldValue = el.value;
                ccNumberInputOldCursor = el.selectionEnd;
            },
            ccNumberInputInputHandler = (e) => {
                let el = e.target,
                    newValue = unmask(el.value),
                    newCursorPosition;

                if (newValue.match(ccNumberPattern)) {
                    newValue = mask(newValue, 4, ccNumberSeparator);

                    newCursorPosition =
                        ccNumberInputOldCursor - checkSeparator(ccNumberInputOldCursor, 4) +
                        checkSeparator(ccNumberInputOldCursor + (newValue.length - ccNumberInputOldValue.length), 4) +
                        (unmask(newValue).length - unmask(ccNumberInputOldValue).length);

                    el.value = (newValue !== "") ? newValue : "";
                } else {
                    el.value = ccNumberInputOldValue;
                    newCursorPosition = ccNumberInputOldCursor;
                }

                el.setSelectionRange(newCursorPosition, newCursorPosition);

                highlightCC(el.value);
            },
            highlightCC = (ccValue) => {
                let ccCardType = '',
                    ccCardTypePatterns = {
                        amex: /^3/,
                        visa: /^4/,
                        mastercard: /^5/,
                        disc: /^6/,

                        genric: /(^1|^2|^7|^8|^9|^0)/,
                    };

                for (const cardType in ccCardTypePatterns) {
                    if (ccCardTypePatterns[cardType].test(ccValue)) {
                        ccCardType = cardType;
                        break;
                    }
                }
            }

        ccNumberInput.addEventListener('keydown', ccNumberInputKeyDownHandler);
        ccNumberInput.addEventListener('input', ccNumberInputInputHandler);
    }
};

export default helper;
