function searchEngine() {
  var input, filter, table, tr, td, i, txtValue;

  input = document.getElementById("querybox");

  filter = input.value.toUpperCase();
  console.log(filter);

  table = document.getElementById("allResults");
    // console.log(table);

    single = table.getElementsByClassName("single-result");
    // console.log(single);

    for (i = 0; i < single.length; i++) {
    // console.log(single.length);

    tw = single[i].getElementsByClassName("single-tweet")[0];
    // console.log(tw);
    if (tw) {

      txtValue = tw.textContent || tw.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        single[i].style.display = "";
        table.style.display = "";
      } else {
        single[i].style.display = "none";
      }
    }       
  }
}