//Used in the Tag Dropdown buttons
function changeTagDisplay(tagSelectVal) {
	//Takes in previously loaded value from php
    tagDisplay = document.getElementById('tagDisplay');
    tagDisplayHidden = document.getElementById('tagDisplayHidden');
    tagDisplayHidden.value = tagSelectVal;
    tagDisplay.value = tagSelectVal;
  }