//Used in the Tag Dropdown buttons
function changeTagDisplay(tagSelectVal) {
	//Takes in previously loaded value from php
    tagDisplay = document.getElementById('tagDisplay');
    tagDisplayHidden = document.getElementById('tagDisplayHidden');
    tagDisplayHidden.value = tagSelectVal;
    tagDisplay.value = tagSelectVal;
  }

  //Used in the Task Sorted Display Dropdown Menu
function changeTaskSortedByDisplay(selectVal) {
	//Takes in previously loaded value from php
    sortedByDisplay = document.getElementById('sortedByDisplay');
    sortedByInput = document.getElementById('sortedByInput');
    sortedByDisplay.innerHTML = selectVal;
    sortedByInput.value = selectVal;
  }