$(document).ready(function() { //perform actions when DOM is ready
  var initialWidth = $('#content pre:first').width(); //store the initial width of code-boxes for the reset on mouse out
  
  $('pre').hover(function() { //on mouse over
    if($(this).height() > $(this).children().height()) { //if <pre> is higher than <code>
      var openSpace = Math.round(($('body').width() - initialWidth) / 2 - 10); //calculate the space to the right with a margin-right of 10px
      
      if($(this).width() == initialWidth) { //start animation only when we are at starting point (initial width)
        $(this).animate({ width : initialWidth + openSpace }, 'fast'); //increase the width with animation
      }
    }
  }, function() { //on mouse out
    $(this).animate({ width : initialWidth }, 'fast'); //reset to initial width with animation
  });
  
  $('#content pre').each(function() { //on each code box do
    $(this)
      .before('<a href="#" class="codeswitch">View this source in Plain Text</a>') //write a code right before the code-box
      .after('<textarea rows="' + ($(this).children().html().split("\n").length-1) + '" cols="50">' + $(this).children().html() + '</textarea>'); //write a textarea with the content of the code-box after it
  });
  
  $('.codeswitch').toggle(function() { //hide code-box and show textarea
    $(this)
      .text('View this source as Highlighted Code') //change text of the link
      .next().hide().next().show(); //first next is code-box, second is textarea
  }, function() { //hide textarea and show code box
    $(this)
      .text('View this source in Plain Text')
      .next().show().next().hide();
  });
});