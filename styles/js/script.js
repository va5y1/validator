/**
 * @file
 */

// alert('Fuck You');
// document.addEventListener('change', sum);
// let a = document.getElementById('edit-table1-1-1').value;

// let b = document.getElementById('edit-table1-1-2').value;

// if (isNaN(a) === true) {
//   a = 1;
// }
// if (isNaN(b) === true) {
//   b = 2;
// }
// let c = a + b;
// function sum(){
//   document.getElementById('edit-table1-1-q1').value = c;
// }
function sumQuartal(el){
    el.forEach(function(element){
      let firstMonth = Math.round(parseFloat(element.previousElementSibling.previousElementSibling.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;
        let secondMonth = Math.round(parseFloat(element.previousElementSibling.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;
        let thirdMonth = Math.round(parseFloat(element.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;

        let quartalSum = firstMonth + secondMonth + thirdMonth;
        if (quartalSum != 0) {
          element.textContent = Math.round(((quartalSum +1)/3)*100)/100;;
        }
        else{
          element.textContent = 0;
        }
    }, false)
  };
//react on any change in tables inputes	that loaded
    newTable.addEventListener('input', function (e) {

      ///@TODO adding quartalsSum
      let firstQuartals = document.querySelectorAll('.q1');
      let secondQuartals = document.querySelectorAll('.q2');
      let thirdQuartals = document.querySelectorAll('.q3');
      let fourthQuartals = document.querySelectorAll('.q4');
      sumQuartal(firstQuartals);
      sumQuartal(secondQuartals);
      sumQuartal(thirdQuartals);
      sumQuartal(fourthQuartals);

      ///////////////////////////
      let quartalsSum = document.querySelectorAll('.YTD');
      quartalsSum.forEach(function(element){
        let qFirst = parseFloat(element.parentNode.querySelector(':nth-child(5)').textContent);
        let qSecond = parseFloat(element.parentNode.querySelector(':nth-child(9)').textContent);
        let qThird = parseFloat(element.parentNode.querySelector(':nth-child(13)').textContent);
        let qFourth = parseFloat(element.previousElementSibling.textContent);
        let ySum = qFirst + qSecond + qThird + qFourth;
        if (ySum != 0) {
          element.textContent = Math.round(((ySum +1)/3)*100)/100;
        }
        else{
          element.textContent = 0;
        }

      }, false);
