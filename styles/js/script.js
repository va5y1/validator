/**
 * @file
 */

// alert('Fuck You');
document.addEventListener('change', sum);
let a = document.getElementById('edit-table1-1-1').value;

let b = document.getElementById('edit-table1-1-2').value;

if (isNaN(a) === true) {
  a = 1;
}
if (isNaN(b) === true) {
  b = 2;
}
let c = a + b;
function sum(){
  document.getElementById('edit-table1-1-q1').value = c;
}

