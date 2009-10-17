/**
 * chained select
 */
$(function()
{
	$('#manufacturer').chainSelect('#model','/modules/ajax/printers.php',
	{ 
		before:function (target) //before request hide the target combobox and display the loading message
		{ 
			$("#loading").css("display","block");
			$(target).css("display","none");
		},
		after:function (target) //after request show the target combobox and hide the loading message
		{ 
			$("#loading").css("display","none");
			$(target).css("display","inline");
		}
	});
	$('#model').chainSelect('#submodel','/modules/ajax/printers.php',
	{ 
		before:function (target) 
		{ 
			$("#loading").css("display","block");
			$(target).css("display","none");
		},
		after:function (target) 
		{ 
			$("#loading").css("display","none");
			$(target).css("display","inline");
		}
	});
});
