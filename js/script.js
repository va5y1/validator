/**
 * @file
 */

let queryMonth = document.querySelectorAll('input.form-edit-month');

let queryQuart = document.querySelectorAll('input.quart');
let queryYtd = document.querySelectorAll('input.ytd');


for (let i = 0; i < queryMonth.length; i++) {
    if (isNaN(queryMonth[i].value)) {
        queryMonth[i].value = 0;
    }
    queryMonth[i].addEventListener(
        'change', function () {
            queryMonth[i].value = parseFloat(this.value);
            for (let j = 0; j < queryQuart.length; j++) { // Загружаємо квартали.
                let getElem = []; // Створюємо масив.
                getElem[j] = j * 3; // Заповнюємо масив (Тут будуть ключі місяці
                            // квартала)
                if (isNaN(queryQuart[j].value)) {
                    queryQuart[j].value = 0;
                }
                if (isNaN(queryMonth[i].value)) {
                    queryMonth[j].value = 0;
                }
                let sum = (queryMonth[getElem[j]].value) * 1
                          + (queryMonth[getElem[j] + 1].value) * 1
                          + (queryMonth[getElem[j] + 2].value) * 1;
                if (sum !== 0) {
                    queryQuart[j].value = (Math.round(((sum + 1) / 3) * 100)) / 100;
                } else {
                    queryQuart[j].value = 0;
                }
                for (let k = 0; k < queryYtd.length; k++) {
                    let getElem = [];
                    getElem[k] = k * 4;
                    let summa = (queryQuart[getElem[k]].value) * 1
                                + (queryQuart[getElem[k] + 1].value) * 1
                                + (queryQuart[getElem[k] + 2].value) * 1
                                + (queryQuart[getElem[k] + 3].value) * 1;
                    if (summa !== 0) {
                        queryYtd[k].value = (Math.round(((summa + 1) / 4) * 100)) / 100;
                    }

                }
            }

        }
    );


}
