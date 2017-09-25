// JavaScript Document
function get_section(flag)
{
	var topic 		= $.trim($('input[name="hid_topic_id"]').val());
	var section		= $.trim($('input[name="hid_section_id"]').val());
	var subsection	= $.trim($('input[name="hid_subsection_id"]').val());
	
	//var topic = $.trim($('select[name="topic"]').val());
	//var section = $.trim($('select[name="section"]').val());
	//var subsection = $.trim($('select[name="subsection"]').val());
	var area = $.trim($('select[name="area"]').val());
	var qtopics = $.trim($('select[name="qtopics"]').val());
	var format = $.trim($('select[name="format1"]').val());
	
	//alert("Topic :"+topic+", Section :"+section+", Subsection :"+subsection+", area :"+area+", qtopics :"+qtopics+", Format :"+format);
	if (topic == "" || topic == null) {
		//$('select[name="section"]').html('<option value="">Select Section</option>');
		$('select[name="subsection"]').html('<option value="">Select SubSection</option>');
		$('select[name="area"]').html('<option value="">Select Area</option>');
		$('select[name="qtopics"]').html('<option value="">Select Qtopics</option>');
		$('select[name="format1"]').html('<option value="">Select Format</option>');
	} else {
		$.post(location.href,{topic:topic,section:section, subsection:subsection, area:area, qtopics:qtopics, format:format, flag:flag, jsubmit:'Get_Section'},function(data){
			var json_string = JSON.parse(data);
			//$('select[name="section"]').html(json_string.section);
			$('select[name="subsection"]').html(json_string.subsection);
			$('select[name="area"]').html(json_string.area);
			$('select[name="qtopics"]').html(json_string.qtopics);
			$('select[name="format1"]').html(json_string.format);
			get_options();
			get_options_setdata(optvals);
		});
	}
}

function get_options()
{
	var qformat 	= $.trim($('select[name="format1"] option:selected').html());
	var num_options = $.trim($('select[name="num_options"]').val());
	var stopic		= $.trim($('input[name="hid_subsection_id"]').val());
	//var stopic 		= $.trim($('select[name="subsection"] option:selected').html());
	var htmldata 	= "";
	//alert("Qformat : "+qformat+", Num of Option :"+num_options+", Stopic :"+stopic);
	/*$('#common_data_div').hide();
	if (stopic == "Reading Comprehension" || stopic == "Common Data") {
		$('#common_data_div').show();
	} else {
		$('#common_data_div').hide();
	}*/
	
            if (qformat == "Descriptive") 
			{
                $('#options_box').html(htmldata);
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
            } 
			else if (qformat == "2 Blank 3+3 Model") 
			{
                htmldata  = '<table><tr><td colspan="2"><legend>First Blank Options</legend></td></tr>';
				htmldata += '<tr><td id="answer_td11" class="cls_ans1"><input type="radio" name="opt1_answer_sel" value="1" onchange="answer_color1(1,1)"   /> Answer<textarea name="options1_1" class="ckeditor"></textarea></td>';
				htmldata += '<td id="answer_td12" class="cls_ans1"><input type="radio" name="opt1_answer_sel" value="2" onchange="answer_color1(1,2)" /> Answer<textarea name="options1_2" class="ckeditor"></textarea></td></tr>';
				htmldata += '<tr><td id="answer_td13" class="cls_ans1"><input type="radio" name="opt1_answer_sel" value="3" onchange="answer_color1(1,3)" /> Answer<textarea name="options1_3" class="ckeditor"></textarea></td><td>&nbsp;</td></tr>';
				htmldata += '<tr><td colspan="2"><legend>Second Blank Options</legend></td></tr>';
				htmldata += '<tr><td id="answer_td21" class="cls_ans2"><input type="radio" name="opt2_answer_sel" value="1" onchange="answer_color1(2,1)" /> Answer<textarea name="options2_1" class="ckeditor"></textarea></td>';
				htmldata += '<td id="answer_td22" class="cls_ans2"><input type="radio" name="opt2_answer_sel" value="2" onchange="answer_color1(2,2)" /> Answer<textarea name="options2_2" class="ckeditor"></textarea></td>';
				htmldata += '<tr><td id="answer_td23" class="cls_ans2"><input type="radio" name="opt2_answer_sel" value="3" onchange="answer_color1(2,3)" /> Answer<textarea name="options2_3" class="ckeditor"></textarea></td><td>&nbsp;</td></tr>';
                htmldata += '</table>';
				$('select[name="num_options"]').val('3');
                $('select[name="num_options"]').select2();
                $('#options_box').html(htmldata);
                $('.ckeditor').each(function(){
                    CKEDITOR.replace( $(this).attr('name') ,{height:"100", width:"100%"});
                });
        
            } 
			else if (qformat == "3 Blank 3+3+3 Model") 
			{
                htmldata  = '<table><tr><td colspan="2"><legend>First Blank Options</legend></td></tr>';
				htmldata += '<tr><td id="answer_td11" class="cls_ans1"><input type="radio" name="opt1_answer_sel" value="1"  onchange="answer_color1(1,1)" /> Answer<textarea name="options1_1" class="ckeditor"></textarea></td>';
				htmldata += '<td id="answer_td12" class="cls_ans1"><input type="radio" name="opt1_answer_sel" value="2"  onchange="answer_color1(1,2)" /> Answer<textarea name="options1_2" class="ckeditor"></textarea></td></tr>';
				htmldata += '<tr><td id="answer_td13" class="cls_ans1"><input type="radio" name="opt1_answer_sel" value="3"  onchange="answer_color1(1,3)" /> Answer<textarea name="options1_3" class="ckeditor"></textarea></td><td>&nbsp;</td></tr>';
				
				htmldata += '<tr><td colspan="2"><legend>Second Blank Options</legend></td></tr>';
				htmldata += '<tr><td id="answer_td21" class="cls_ans2"><input type="radio" name="opt2_answer_sel" value="1"  onchange="answer_color1(2,1)" /> Answer<textarea name="options2_1" class="ckeditor"></textarea></td>';
				htmldata += '<td id="answer_td22" class="cls_ans2"><input type="radio" name="opt2_answer_sel" value="2"  onchange="answer_color1(2,2)" /> Answer<textarea name="options2_2" class="ckeditor"></textarea></td></tr>';
				htmldata += '<tr><td id="answer_td23" class="cls_ans2"><input type="radio" name="opt2_answer_sel" value="3" onchange="answer_color1(2,3)"  /> Answer<textarea name="options2_3" class="ckeditor"></textarea></td><td>&nbsp;</td></tr>';
				
				htmldata += '<tr><td colspan="2"><legend>Third Blank Options</legend></td></tr>';
				htmldata += '<tr><td id="answer_td31" class="cls_ans3"><input type="radio" name="opt3_answer_sel" value="1"  onchange="answer_color1(3,1)"  /> Answer<textarea name="options3_1" class="ckeditor"></textarea></td>';
				htmldata += '<td id="answer_td32" class="cls_ans3"><input type="radio" name="opt3_answer_sel" value="2"  onchange="answer_color1(3,2)"  /> Answer<textarea name="options3_2" class="ckeditor"></textarea></td></tr>';
				htmldata += '<tr><td id="answer_td33" class="cls_ans3"><input type="radio" name="opt3_answer_sel" value="3"  onchange="answer_color1(3,3)"  /> Answer<textarea name="options3_3" class="ckeditor"></textarea></td><td>&nbsp;</td></tr>';
				
				htmldata += '</table>';
                $('select[name="num_options"]').val('3');
                $('select[name="num_options"]').select2();
                $('#options_box').html(htmldata);
                $('.ckeditor').each(function(){
                    CKEDITOR.replace( $(this).attr('name') ,{height:"100", width:"100%"});
                });
        
            } 
			else if (qformat == "Select In") 
			{
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
                $('#options_box').html('<textarea name="answer" class="ckeditor"></textarea>');
                $('.ckeditor').each(function(){
                    CKEDITOR.replace( $(this).attr('name') ,{height:"100", width:"100%"});
                });        
            } 
			else if (qformat == "Multiple Options With Multiple Answer") 
			{
                $('select[name="num_options"]').val(num_options);
                $('select[name="num_options"]').select2();
				htmldata = '<table><tr>';
                for (var i = 1; i <= num_options; i++) 
				{
					if(i%2 == 1)
					{
						htmldata += '<tr>';
						htmldata += '<td id="answer_td'+i+'" class="cls_ans"><input type="checkbox" id="chk'+i+'" onchange="chk_answer_color('+i+')" name="answer_sel[]" value="'+i+'" /> Answer<textarea name="options'+i+'" class="ckeditor"></textarea></td>';
					}
					else
					{
						htmldata += '<td id="answer_td'+i+'" class="cls_ans"><input type="checkbox" id="chk'+i+'" onchange="chk_answer_color('+i+')" name="answer_sel[]" value="'+i+'" /> Answer<textarea name="options'+i+'" class="ckeditor"></textarea></td>';
					}
                }
				htmldata += '</tr></table>';
                $('#options_box').html(htmldata);
                $('.ckeditor').each(function(){
                    CKEDITOR.replace( $(this).attr('name') ,{height:"100", width:"100%"});
                });
            } 
			else if (qformat == "Numeric Entry Single Blank" || qformat == "Numeric Entry Single Blank Suffix" || qformat == "Numeric Entry Single Blank Prefix") 
			{
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer" value="Enter Answer" onblur="toggle_text(\'input\',\'answer\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer\',\'Enter Answer\',\'1\');" />';
                if (qformat == "Numeric Entry Single Blank Prefix") 
				{
                    htmldata = '<input type="text" name="prefix" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix\',\'Enter Prefix\',\'1\');" />' + htmldata;
                }
				else if (qformat == "Numeric Entry Single Blank Suffix") 
				{
                    htmldata = '<input type="text" name="suffix" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix\',\'Enter Suffix\',\'1\');" />' + htmldata;
                }
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Numeric Entry Two Blank" || qformat == "Numeric Entry Two Blank Suffix" || qformat == "Numeric Entry Two Blank Prefix") 
			{
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer1" value="Enter Answer" onblur="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'1\');" /> <input type="text" name="answer2" value="Enter Answer" onblur="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'1\');" />';
                if (qformat == "Numeric Entry Two Blank Prefix") 
				{
                    htmldata = '<input type="text" name="prefix1" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'1\');" /> <input type="text" name="prefix2" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'1\');" />' + htmldata;
                } 
				else if (qformat == "Numeric Entry Two Blank Suffix") 
				{
                    htmldata = '<input type="text" name="suffix1" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'1\');" /> <input type="text" name="suffix2" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'1\');" />' + htmldata;
                }
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Numeric Entry Two Blank Vertical" || qformat == "Numeric Entry Two Blank Vertical Suffix" || qformat == "Numeric Entry Two Blank Vertical Prefix") 
			{
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer1" value="Enter Answer" onblur="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'1\');" /> <input type="text" name="answer2" value="Enter Answer" onblur="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'1\');" />';
                if (qformat == "Numeric Entry Two Blank Vertical Prefix") 
				{
                    htmldata = '<input type="text" name="prefix1" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'1\');" /> <input type="text" name="prefix2" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'1\');" />' + htmldata;
                } 
				else if (qformat == "Numeric Entry Two Blank Vertical Suffix") 
				{
                    htmldata = '<input type="text" name="suffix1" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'1\');" /> <input type="text" name="suffix2" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'1\');" />' + htmldata;
                }
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Numeric Entry Two Blank Fraction" || qformat == "Numeric Entry Two Blank Fraction Prefix" || qformat == "Numeric Entry Two Blank Fraction Suffix") 
			{
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer1" value="Enter Answer" onblur="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'1\');" /><hr/><input type="text" name="answer2" value="Enter Answer" onblur="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'1\');" />';
                if (qformat == "Numeric Entry Two Blank Fraction Prefix") 
				{
                    htmldata = '<input type="text" name="prefix1" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'1\');" />' + htmldata;
                } 
				else if (qformat == "Numeric Entry Two Blank Fraction Suffix") 
				{
                    htmldata = '<input type="text" name="suffix1" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'1\');" />' + htmldata;
                }
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Numeric Entry Three Blank" || qformat == "Numeric Entry Three Blank Prefix" || qformat == "Numeric Entry Three Blank Suffix") 
			{
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer1" value="Enter Answer" onblur="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'1\');" /> <input type="text" name="answer2" value="Enter Answer" onblur="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'1\');" /> <input type="text" name="answer3" value="Enter Answer" onblur="toggle_text(\'input\',\'answer3\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer3\',\'Enter Answer\',\'1\');" />';
                if (qformat == "Numeric Entry Three Blank Prefix") 
				{
                    htmldata = '<input type="text" name="prefix1" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'1\');" /> <input type="text" name="prefix2" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'1\');" /> <input type="text" name="prefix3" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix3\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix3\',\'Enter Prefix\',\'1\');" />' + htmldata;
                } 
				else if (qformat == "Numeric Entry Three Blank Suffix") 
				{
                    htmldata = '<input type="text" name="suffix1" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'1\');" /> <input type="text" name="suffix2" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'1\');" /> <input type="text" name="suffix3" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix3\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix3\',\'Enter Suffix\',\'1\');" />' + htmldata;
                }
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Numeric Entry Three Blank Vertical" || qformat == "Numeric Entry Three Blank Vertical Prefix" || qformat == "Numeric Entry Three Blank Vertical Suffix") 
			{
                $('select[name="num_options"]').val('0');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer1" value="Enter Answer" onblur="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer1\',\'Enter Answer\',\'1\');" /> <input type="text" name="answer2" value="Enter Answer" onblur="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer2\',\'Enter Answer\',\'1\');" /> <input type="text" name="answer3" value="Enter Answer" onblur="toggle_text(\'input\',\'answer3\',\'Enter Answer\',\'0\');" onfocus="toggle_text(\'input\',\'answer3\',\'Enter Answer\',\'1\');" />';
                if (qformat == "Numeric Entry Three Blank Vertical Prefix") 
				{
                    htmldata = '<input type="text" name="prefix1" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix1\',\'Enter Prefix\',\'1\');" /> <input type="text" name="prefix2" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix2\',\'Enter Prefix\',\'1\');" /> <input type="text" name="prefix3" value="Enter Prefix" onblur="toggle_text(\'input\',\'prefix3\',\'Enter Prefix\',\'0\');" onfocus="toggle_text(\'input\',\'prefix3\',\'Enter Prefix\',\'1\');" />' + htmldata;
                } 
				else if (qformat == "Numeric Entry Three Blank Vertical Suffix") 
				{
                    htmldata = '<input type="text" name="suffix1" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix1\',\'Enter Suffix\',\'1\');" /> <input type="text" name="suffix2" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix2\',\'Enter Suffix\',\'1\');" /> <input type="text" name="suffix3" value="Enter Suffix" onblur="toggle_text(\'input\',\'suffix3\',\'Enter Suffix\',\'0\');" onfocus="toggle_text(\'input\',\'suffix3\',\'Enter Suffix\',\'1\');" />' + htmldata;
                }
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Multiple Options With Single Answer" || qformat == "1 Blank") 
			{
                if (num_options != "" && num_options != null) {
                    num_options = parseInt(num_options);
                    htmldata = "<table><tr>";
                    for (var i = 1; i <= num_options; i++) 
					{
						if(i%2 == 1)
						{
							htmldata += '<tr>';
                        	htmldata += '<td id="answer_td'+i+'" class="cls_ans"><input type="radio" name="answer_sel" id="cls_answer" value="'+i+'" onchange="answer_color('+i+')"  /> Answer<textarea name="options'+i+'" class="ckeditor"></textarea></td>';
						}
						else
						{
							htmldata += '<td id="answer_td'+i+'" class="cls_ans"><input type="radio" name="answer_sel" id="cls_answer"  value="'+i+'"  onchange="answer_color('+i+')" /> Answer<textarea name="options'+i+'" class="ckeditor"></textarea></td>';
						}
					}
					htmldata += '</tr></table>';
                    $('select[name="num_options"]').val(num_options);
                	$('select[name="num_options"]').select2();
                    $('#options_box').html(htmldata);
                    $('.ckeditor').each(function(){
                        CKEDITOR.replace( $(this).attr('name') ,{height:"100", width:"100%"});
                    });
                }
            } 
			else if (qformat == "Select All") 
			{
                if (num_options != "" && num_options != null) 
				{
                    num_options = parseInt(num_options);
                    htmldata = "<table><tr>";
                    for (var i = 1; i <= num_options; i++) 
					{
						if(i%2 == 1)
						{
							htmldata += '<tr>';
                        	htmldata += '<td id="answer_td'+i+'" class="cls_ans"><input type="checkbox" id="chk'+i+'" name="answer_sel[]" onchange="chk_answer_color('+i+')" value="'+i+'" /> Answer<textarea name="options'+i+'" class="ckeditor"></textarea></td>';
						}
						else
						{
							htmldata += '<td id="answer_td'+i+'" class="cls_ans"><input type="checkbox" id="chk'+i+'" name="answer_sel[]" onchange="chk_answer_color('+i+')" value="'+i+'" /> Answer<textarea name="options'+i+'" class="ckeditor"></textarea></td>';
						}
                    }
					htmldata += '</tr></table>';
                    $('#options_box').html(htmldata);
                    $('select[name="num_options"]').val(num_options);
                	$('select[name="num_options"]').select2();
                    $('.ckeditor').each(function(){
                        CKEDITOR.replace( $(this).attr('name') ,{height:"100", width:"100%"});
                    });
                }
        
            } 
			else if (qformat == "Cryptogram") 
			{
                //$('select[name="num_options"]').val('5');
                htmldata = '<br/><br/><input type="text" name="answer" placeholder="Enter Answer e.g. A,B,C,D"  style="width:350px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Match the column") 
			{
                $('select[name="num_options"]').val('');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer" placeholder="Enter Answer e.g. 1-a,2-b,3-c" style="width:350px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "True False") 
			{
				htmldata = "<table><tr>";
				for (var i = 1; i <= 2; i++) 
				{
					htmldata += '<td id="answer_td'+i+'" class="cls_ans"><input type="radio" name="answer_sel" value="'+i+'"  onchange="answer_color('+i+')" /> Answer<textarea name="options'+i+'" class="ckeditor"></textarea></td>';
				}
				htmldata += '</tr></table>';
				$('select[name="num_options"]').val('2');
                $('select[name="num_options"]').select2();
				$('#options_box').html(htmldata);
				$('.ckeditor').each(function(){
					CKEDITOR.replace( $(this).attr('name') ,{height:"100", width:"100%"});
				});
            } 
			else if (qformat == "Expand In") 
			{
                $('select[name="num_options"]').val('');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer" placeholder="Enter Answer" style="width:350px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Spot Errors") 
			{
                $('select[name="num_options"]').val('');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/><input type="text" name="answer" placeholder="Enter Answer" style="width:350px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Letter Swap") 
			{
                $('select[name="num_options"]').val('');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/>';
                htmldata += '<input type="text" name="answer" placeholder="Enter Answer e.g. TOP,CAT" style="width:350px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Word Search Box") 
			{
                $('select[name="num_options"]').val('');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/>';
                htmldata += '<input type="text" name="answer" placeholder="Enter Answer e.g. 10,10;;;A,B,E,D,E,F,G,O,A,T,H;;;2,3,4;;7,8,9,10" style="width:400px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Word Search Letter") 
			{
                $('select[name="num_options"]').val('');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/>';
                htmldata += '<input type="text" name="answer" placeholder="Enter Answer e.g. A,B,E,D,E,F,G,O,A,T,H;;;2,3,4;;7,8,9,10" style="width:400px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else if (qformat == "Simple Crossword") 
			{
                $('select[name="num_options"]').val('');
                $('select[name="num_options"]').select2();
                htmldata = '<br/><br/>';
                htmldata += '<input type="text" name="answer" placeholder="Enter Answer e.g. A1-B,E,D;;A2-A,N,T;;;D1-S,O,N;;D3-S,U,N" style="width:400px;" />';
                $('#options_box').html(htmldata);
        
            } 
			else 
			{
                $('#options_box').html(htmldata);
                $('select[name="num_options"]').val('');
            }
}
function get_options_setdata(optval)
{
	var qformat 	= $.trim($('select[name="format1"]').val());
	var num_options = $.trim($('select[name="num_options"]').val());
	var htmldata 	= "";
	
	//alert("Qformat :"+qformat+", Num options :"+num_options);
	
	if (qformat == "1" || qformat == "5") //multiple-options-with-single-answer  fillin-1
	{ 
		if (num_options != "" && num_options != null) {
			num_options = parseInt(num_options);
			for (var i = 1; i <= num_options; i++) 
			{
				$('textarea[name="options'+i+'"]').html($('#edit_opts'+i).html());
			}
			var opt_num = $('.ans_edit[value="1"]').attr('id');
			opt_num = opt_num.replace(/\D/gi,"");
			$('input[name="answer_sel"][value="'+opt_num+'"]').attr('checked','true');
			$('select[name="num_options"]').val(optval);
			answer_color(opt_num);		
		}
	} 
	else if (qformat == "2" || qformat == "20") //multiple-options-with-multiple-answer selectall
	{ 
		if (num_options != "" && num_options != null) 
		{
			num_options = parseInt(num_options);
			for (var i = 1; i <= num_options; i++) 
			{
				$('textarea[name="options'+i+'"]').html($('#edit_opts'+i).html());
			}
			$( '.ans_edit[value="1"]' ).each(function( index ) {
				var opt_num = $(this).attr('id');
				opt_num = opt_num.replace(/\D/gi,"");
				$('input[name="answer_sel[]"][value="'+opt_num+'"]').attr('checked','true');
				chk_answer_color(opt_num);
			});
			$('select[name="num_options"]').val(optval);
		}
	} 
	else if (qformat == "8") //select-in
	{ 
		$('textarea[name="answer"]').html($('#edit_opts1').html());
		$('select[name="num_options"]').val('0');
	} 
	else if (qformat == "7") //3+3+3-model
	{ 
		for (var i = 1; i <= 3; i++) 
		{
			for (var j = 1; j <= 3; j++) 
			{
				$('textarea[name="options'+i+'_'+j+'"]').html($('#edit_opts'+i+'_'+j).html());
			}
			var opt_num = $('.ans_edit'+i+'[value="1"]').attr('id');
			opt_num = opt_num.replace(/^edit_answer\d+\_(\d+)$/gi,"$1"); //edit_answer2_3
			$('input[name="opt'+i+'_answer_sel"][value="'+opt_num+'"]').attr('checked','true');
			answer_color3plus(i,opt_num);		
		} //options1_1 opt1_answer_sel
	} 
	else if (qformat == "6") //3+3-model
	{ 
		for (var i = 1; i <= 2; i++) 
		{
			for (var j = 1; j <= 3; j++) 
			{
				$('textarea[name="options'+i+'_'+j+'"]').html($('#edit_opts'+i+'_'+j).html());
			}
			var opt_num = $('.ans_edit'+i+'[value="1"]').attr('id');
			opt_num = opt_num.replace(/^edit_answer\d+\_(\d+)$/gi,"$1"); //edit_answer2_3
			$('input[name="opt'+i+'_answer_sel"][value="'+opt_num+'"]').attr('checked','true');
			answer_color3plus(i,opt_num);		
		}
	} 
	else if (qformat == "3") //numeric-entry-single-blank
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "4" || qformat == "30") //numeric-entry-two-blank
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
	} 
	else if (qformat == "10" || qformat == "31") //numeric-entry-three-blank
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
		$('input[name="answer3"]').val($('#edit_answer3').val());
	} 
	else if (qformat == "11") //numeric-entry-two-blank-fraction
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
	} 
	else if (qformat == "12") //numeric-entry-single-blank-prefix
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
		$('input[name="prefix"]').val($('#suf_pre').val());
	} 
	else if (qformat == "13" || qformat == "32") //numeric-entry-two-blank-prefix
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
		$('input[name="prefix1"]').val($('#suf_pre1').val());
		$('input[name="prefix2"]').val($('#suf_pre2').val());
	} 
	else if (qformat == "14" || qformat == "33") //numeric-entry-three-blank-prefix
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
		$('input[name="answer3"]').val($('#edit_answer3').val());
		$('input[name="prefix1"]').val($('#suf_pre1').val());
		$('input[name="prefix2"]').val($('#suf_pre2').val());
		$('input[name="prefix3"]').val($('#suf_pre3').val());
	} 
	else if (qformat == "15") //numeric-entry-two-blank-fraction-prefix
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
		$('input[name="prefix1"]').val($('#suf_pre1').val());
	} 
	else if (qformat == "16") //numeric-entry-single-blank-suffix
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
		$('input[name="suffix"]').val($('#suf_pre').val());
	} 
	else if (qformat == "17" || qformat == "34") //numeric-entry-two-blank-suffix
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
		$('input[name="suffix1"]').val($('#suf_pre1').val());
		$('input[name="suffix2"]').val($('#suf_pre2').val());
	} 
	else if (qformat == "18" || qformat == "35") //numeric-entry-three-blank-suffix
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
		$('input[name="answer3"]').val($('#edit_answer3').val());
		$('input[name="suffix1"]').val($('#suf_pre1').val());
		$('input[name="suffix2"]').val($('#suf_pre2').val());
		$('input[name="suffix3"]').val($('#suf_pre3').val());
	} 
	else if (qformat == "19") //numeric-entry-two-blank-fraction-suffix
	{ 
		$('input[name="answer1"]').val($('#edit_answer1').val());
		$('input[name="answer2"]').val($('#edit_answer2').val());
		$('input[name="suffix1"]').val($('#suf_pre1').val());
	} 
	else if (qformat == "21") //cryptogram
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "22") //matchthecolumn
	{
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "23")  //true-false
	{
		for (var i = 1; i <= num_options; i++) {
			$('textarea[name="options'+i+'"]').html($('#edit_opts'+i).html());
		}
		var opt_num = $('.ans_edit[value="1"]').attr('id');
		opt_num = opt_num.replace(/\D/gi,"");
		$('input[name="answer_sel"][value="'+opt_num+'"]').attr('checked','true');
		answer_color(opt_num);		
	} 
	else if (qformat == "24") //expandin
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "25") //spoterrors
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "26") //letterswap
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "27") //wordsearchbox
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "28") //wordsearchletter
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "29") //scrossword
	{ 
		$('input[name="answer"]').val($('#edit_answer').val());
	} 
	else if (qformat == "9") //descriptive
	{ 
	}
}
function validate_frm()
{
	if (confirm("Do you really want to submit the question?")) 
	{
		var err = "";
		var topic 		= $.trim($('input[name="hid_topic_id"]').val());
		var section		= $.trim($('input[name="hid_section_id"]').val());
		
		var subsection	= $.trim($('input[name="hid_subsection_id"]').val());
		var area 		= $.trim($('select[name="area"]').val());
		var qtopics 	= $.trim($('select[name="qtopics"]').val());
		
		var format 		= $.trim($('select[name="format1"]').val());
		var num_options = $.trim($('select[name="num_options"]').val());
		var cluster 	= $.trim($('select[name="cluster"]').val());
		
		var clustertype = $.trim($('select[name="clustertype"]').val());
		var testcluster = $.trim($('input[name="testcluster"]').val());
		//var level 		= $.trim($('input[name="level"]').val());
		var question 	= $.trim(CKEDITOR.instances['question'].getData());
		var commondata 	= $.trim(CKEDITOR.instances['commondata'].getData());
		//var qformat 	= $.trim($('select[name="format"] option:selected').html());
			
		//alert(format);
		if (topic == "" || topic == null) 
		{
			alert("Provide Test Topic\n");
			return false;
		}
		else if (section == "" || section == null) 
		{
			alert("Provide Section\n");
			return false;
		}
		else if (subsection == "" || subsection == null) 
		{
			alert("Provide Sub Section\n");
			return false;
		}
		else if (area == "" || area == null) 
		{
			alert("Select Area\n");
			return false;
		}
		else if (qtopics == "" || qtopics == null) 
		{
			alert("Select Qtopics\n");
			return false;
		}
		if (format == "" || format == null) 
		{
			alert("Select Format\n");
			return false;
		}
//		if ((num_options == "" || num_options == null || num_options == "0") && (format == "5" || format == "6" || format == "7" || format == "2" || format == "1" || format == "2" || format == "20")) 
//		{
//			alert("Select Number of Options\n");
//			return false;
//		}		
		if (!testcluster.match(/^\d+$/)) {
			alert("Provide Test Cluster\n");
			return false;
		}
		if (!cluster.match(/^\d+$/)) {
			alert("Select Sort order/Cluster Cluster\n");
			return false;
		}
		if (clustertype == "" || clustertype == null) {
			alert("Select Cluster Number Of Questions\n");
			return false;
		}
		if (clustertype != 1 && commondata == "") {
			alert("Enter Common data\n");
			return false;
		}
//		if (level == "" || level == null) {
//			err += "Enter Level\n";
//		}
		if (question == "" || question == null) {
			alert("Enter Question\n");
			return false;
		}
		
		if (format == 6) //"2 Blank 3+3 Model"
		{
			var options1 = $.trim(CKEDITOR.instances['options1_1'].getData());
			var options2 = $.trim(CKEDITOR.instances['options1_2'].getData());
			var options3 = $.trim(CKEDITOR.instances['options1_3'].getData());
			var options4 = $.trim(CKEDITOR.instances['options2_1'].getData());
			var options5 = $.trim(CKEDITOR.instances['options2_2'].getData());
			var options6 = $.trim(CKEDITOR.instances['options2_3'].getData());
			
			if (options1 == "" || options1 == null) 
			{
				err += "Enter first blank option 1\n";
			}
			if (options2 == "" || options2 == null) 
			{
				err += "Enter first blank option 2\n";
			}
			if (options3 == "" || options3 == null) 
			{
				err += "Enter first blank option 3\n";
			}
			if (options4 == "" || options4 == null) 
			{
				err += "Enter second blank option 1\n";
			}
			if (options5 == "" || options5 == null) 
			{
				err += "Enter second blank option 2\n";
			}
			if (options6 == "" || options6 == null) 
			{
				err += "Enter second blank option 3\n";
			}
			if (!$("input:radio[name='opt1_answer_sel']").is(":checked")) 
			{
				err += "Enter first blank answer\n";
			}
			if (!$("input:radio[name='opt2_answer_sel']").is(":checked")) 
			{
				err += "Enter second blank answer\n";
			}

		} 
		else if (format == 7)//"3 Blank 3+3+3 Model" 
		{
			var options1 = $.trim(CKEDITOR.instances['options1_1'].getData());
			var options2 = $.trim(CKEDITOR.instances['options1_2'].getData());
			var options3 = $.trim(CKEDITOR.instances['options1_3'].getData());
			var options4 = $.trim(CKEDITOR.instances['options2_1'].getData());
			var options5 = $.trim(CKEDITOR.instances['options2_2'].getData());
			var options6 = $.trim(CKEDITOR.instances['options2_3'].getData());
			var options7 = $.trim(CKEDITOR.instances['options3_1'].getData());
			var options8 = $.trim(CKEDITOR.instances['options3_2'].getData());
			var options9 = $.trim(CKEDITOR.instances['options3_3'].getData());
			
			if (options1 == "" || options1 == null) {
				err += "Enter first blank option 1\n";
			}
			if (options2 == "" || options2 == null) {
				err += "Enter first blank option 2\n";
			}
			if (options3 == "" || options3 == null) {
				err += "Enter first blank option 3\n";
			}
			if (options4 == "" || options4 == null) {
				err += "Enter second blank option 1\n";
			}
			if (options5 == "" || options5 == null) {
				err += "Enter second blank option 2\n";
			}
			if (options6 == "" || options6 == null) {
				err += "Enter second blank option 3\n";
			}
			if (options7 == "" || options7 == null) {
				err += "Enter third blank option 1\n";
			}
			if (options8 == "" || options8 == null) {
				err += "Enter third blank option 2\n";
			}
			if (options9 == "" || options9 == null) {
				err += "Enter third blank option 3\n";
			}
			if (!$("input:radio[name='opt1_answer_sel']").is(":checked")) {
				err += "Enter first blank answer\n";
			}
			if (!$("input:radio[name='opt2_answer_sel']").is(":checked")) {
				err += "Enter second blank answer\n";
			}			
			if (!$("input:radio[name='opt3_answer_sel']").is(":checked")) {
				err += "Enter third blank answer\n";
			}

		} 
		else if (format == 8)//"Select In" 
		{
			var answer = $.trim(CKEDITOR.instances['answer'].getData());
			if (answer == "" || answer == null) {
				err += "Enter answer\n";
			}

		} 
		else if (format == 3 || format ==  16|| format == 12) //"Numeric Entry Single Blank","Numeric Entry Single Blank Suffix","Numeric Entry Single Blank Prefix"
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null || answer.match(/\s/)) {
				err += "Enter valid answer\n";
			}
			if (qformat == "Numeric Entry Single Blank Suffix") {
				var suffix = $.trim($('input[name="suffix"]').val());
				if (suffix == "" || suffix == null) {
					err += "Enter valid suffix\n";
				}
			} else if (qformat == "Numeric Entry Single Blank Prefix") {
				var prefix = $.trim($('input[name="prefix"]').val());
				if (prefix == "" || prefix == null) {
					err += "Enter valid prefix\n";
				}
			}

		} 
		else if (format == 4 || format == 17 || format == 13) //"Numeric Entry Two Blank","Numeric Entry Two Blank Suffix","Numeric Entry Two Blank Prefix"
		{
			var answer1 = $.trim($('input[name="answer1"]').val());
			if (answer1 == "" || answer1 == null || answer1.match(/\s/)) {
				err += "Enter valid first box answer\n";
			}
			if (qformat == "Numeric Entry Two Blank Suffix" || qformat == "Numeric Entry Two Blank Vertical Suffix") {
				var suffix1 = $.trim($('input[name="suffix1"]').val());
				if (suffix1 == "" || suffix1 == null) {
					err += "Enter valid first box suffix\n";
				}
			} else if (qformat == "Numeric Entry Two Blank Prefix" || qformat == "Numeric Entry Two Blank Vertical Prefix") {
				var prefix1 = $.trim($('input[name="prefix1"]').val());
				if (prefix1 == "" || prefix1 == null) {
					err += "Enter valid first box prefix\n";
				}
			}
			var answer2 = $.trim($('input[name="answer2"]').val());
			if (answer2 == "" || answer2 == null || answer2.match(/\s/)) {
				err += "Enter valid second box answer\n";
			}
			if (qformat == "Numeric Entry Two Blank Suffix" || qformat == "Numeric Entry Two Blank Vertical Suffix") {
				var suffix2 = $.trim($('input[name="suffix2"]').val());
				if (suffix2 == "" || suffix2 == null) {
					err += "Enter valid second box suffix\n";
				}
			} else if (qformat == "Numeric Entry Two Blank Prefix" || qformat == "Numeric Entry Two Blank Vertical Prefix") {
				var prefix2 = $.trim($('input[name="prefix2"]').val());
				if (prefix2 == "" || prefix2 == null) {
					err += "Enter valid second box prefix\n";
				}
			}

		} 
		else if (format == 11 || format == 15 || format == 19) //"Numeric Entry Two Blank Fraction","Numeric Entry Two Blank Fraction Prefix","Numeric Entry Two Blank Fraction Suffix"
		{
			var answer1 = $.trim($('input[name="answer1"]').val());
			if (answer1 == "" || answer1 == null || answer1.match(/\s/)) {
				err += "Enter valid first box answer\n";
			}
			var answer2 = $.trim($('input[name="answer2"]').val());
			if (answer2 == "" || answer2 == null || answer2.match(/\s/)) {
				err += "Enter valid second box answer\n";
			}
			if (num_options == "Numeric Entry Two Blank Fraction Suffix") {
				var suffix1 = $.trim($('input[name="suffix1"]').val());
				if (suffix1 == "" || suffix1 == null) {
					err += "Enter valid suffix\n";
				}
			} else if (num_options == "Numeric Entry Two Blank Fraction Prefix") {
				var prefix1 = $.trim($('input[name="prefix1"]').val());
				if (prefix1 == "" || prefix1 == null) {
					err += "Enter valid prefix\n";
				}
			}

		} 
		else if (format == 10 || format == 14 || format == 18) //"Numeric Entry Three Blank","Numeric Entry Three Blank Prefix","Numeric Entry Three Blank Suffix"
		{
			var answer1 = $.trim($('input[name="answer1"]').val());
			if (answer1 == "" || answer1 == null || answer1.match(/\s/)) {
				err += "Enter valid first box answer\n";
			}
			if (qformat == "Numeric Entry Three Blank Suffix" || qformat == "Numeric Entry Three Blank Vertical Suffix") {
				var suffix1 = $.trim($('input[name="suffix1"]').val());
				if (suffix1 == "" || suffix1 == null) {
					err += "Enter valid first box suffix\n";
				}
			} else if (qformat == "Numeric Entry Three Blank Prefix" || qformat == "Numeric Entry Three Blank Vertical Prefix") {
				var prefix1 = $.trim($('input[name="prefix1"]').val());
				if (prefix1 == "" || prefix1 == null) {
					err += "Enter valid first box prefix\n";
				}
			}
			var answer2 = $.trim($('input[name="answer2"]').val());
			if (answer2 == "" || answer2 == null || answer2.match(/\s/)) {
				err += "Enter valid second box answer\n";
			}
			if (qformat == "Numeric Entry Three Blank Suffix" || qformat == "Numeric Entry Three Blank Vertical Suffix") {
				var suffix2 = $.trim($('input[name="suffix2"]').val());
				if (suffix2 == "" || suffix2 == null) {
					err += "Enter valid second box suffix\n";
				}
			} else if (qformat == "Numeric Entry Three Blank Prefix" || qformat == "Numeric Entry Three Blank Vertical Prefix") {
				var prefix2 = $.trim($('input[name="prefix2"]').val());
				if (prefix2 == "" || prefix2 == null) {
					err += "Enter valid second box prefix\n";
				}
			}
			var answer3 = $.trim($('input[name="answer3"]').val());
			if (answer3 == "" || answer3 == null || answer3.match(/\s/)) {
				err += "Enter valid third box answer\n";
			}
			if (qformat == "Numeric Entry Three Blank Suffix" || qformat == "Numeric Entry Three Blank Vertical Suffix") {
				var suffix3 = $.trim($('input[name="suffix3"]').val());
				if (suffix3 == "" || suffix3 == null) {
					err += "Enter valid third box suffix\n";
				}
			} else if (qformat == "Numeric Entry Three Blank Prefix" || qformat == "Numeric Entry Three Blank Vertical Prefix") {
				var prefix3 = $.trim($('input[name="prefix3"]').val());
				if (prefix3 == "" || prefix3 == null) {
					err += "Enter valid third box prefix\n";
				}
			}

		} 
		else if (format == 20)//"Select All" 
		{
			for (var i = 1; i <= num_options; i++)
			{
				var options1 = $.trim(CKEDITOR.instances['options'+i].getData());
				if (options1 == "" || options1 == null) {
					err += "Enter option "+i+"\n";
				}
			}
			if (!$("input:checkbox[name='answer_sel[]']").is(":checked")) {
				err += "Enter answer\n";
			}	
			/*else if ($("input:checkbox[name='answer_sel[]']:checked").length != num_answers) {
				err += "Enter only "+num_answers+" answer\n";
			}*/

		} 
		else if (format == 2) //Multiple Options With Multiple Answer
		{
			var options1 = $.trim(CKEDITOR.instances['options1'].getData());
			var options2 = $.trim(CKEDITOR.instances['options2'].getData());
			var options3 = $.trim(CKEDITOR.instances['options3'].getData());
			var options4 = $.trim(CKEDITOR.instances['options4'].getData());
			var options5 = $.trim(CKEDITOR.instances['options5'].getData());
			var options6 = $.trim(CKEDITOR.instances['options6'].getData());
			if (options1 == "" || options1 == null) {
				err += "Enter option 1\n";
			}
			if (options2 == "" || options2 == null) {
				err += "Enter option 2\n";
			}
			if (options3 == "" || options3 == null) {
				err += "Enter option 3\n";
			}
			if (options4 == "" || options4 == null) {
				err += "Enter option 4\n";
			}
			if (options5 == "" || options5 == null) {
				err += "Enter option 5\n";
			}
			if (options6 == "" || options6 == null) {
				err += "Enter option 6\n";
			}
			if (!$("input:checkbox[name='answer_sel[]']").is(":checked")) {
				err += "Enter answer\n";
			}	
			else if ($("input:checkbox[name='answer_sel[]']:checked").length != 2) {
				err += "Enter only 2 answer\n";
			}

		} 
		else if (format == 1 || format == 5) //Multiple Options With Single Answer, "1 Blank"
		{
			for (var i = 1; i <= num_options; i++)
			{
				var options1 = $.trim(CKEDITOR.instances['options'+i].getData());
				if (options1 == "" || options1 == null) {
					err += "Enter option "+i+"\n";
				}
			}
			if (!$("input:radio[name='answer_sel']").is(":checked")) {
				err += "Enter answer\n";
			}

		} 
		else if (format == 21)//"Cryptogram" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			/*var str = '^[a-z]';
			for (var i = 2; i <= num_options; i++) {
				str += '\,[a-z]';
			}
			str += '$';
			var newReg = new RegExp(str,'i');
			if (!answer.match(newReg)) {
				err += "Enter valid answer\n";
			}*/
			if (answer == "" || answer == null || answer.match(/\s/)) {
				err += "Enter valid answer\n";
			}

		} 
		else if (format == 22)//"Match the column" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null) {
				err += "Enter valid answer\n";
			}

		} 
		else if (format == 23)//"True False" 
		{
			for (var i = 1; i <= 2; i++)
			{
				var options1 = $.trim(CKEDITOR.instances['options'+i].getData());
				if (options1 == "" || options1 == null) {
					err += "Enter option "+i+"\n";
				}
			}
			if (!$("input:radio[name='answer_sel']").is(":checked")) {
				err += "Enter answer\n";
			}

		} 
		else if (format == 24)//"Expand In" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null) {
				err += "Enter valid answer\n";
			}

		} 
		else if (format == 25)//"Spot Errors" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null) {
				err += "Enter valid answer\n";
			}

		} 
		else if (format == 26)//"Letter Swap" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null) 
			{
				err += "Enter valid answer\n";
			}

		} 
		else if (format == 27)//"Word Search Box" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null) {
				err += "Enter valid answer\n";
			}

		} 
		else if (format == 28)//"Word Search Letter" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null) {
				err += "Enter valid answer\n";
			}
		} 
		else if (format == 29)//"Simple Crossword" 
		{
			var answer = $.trim($('input[name="answer"]').val());
			if (answer == "" || answer == null) {
				err += "Enter valid answer\n";
			}

		}
				
		if (err != "") 
		{
			alert(err);
			return false;
		} 
		else 
		{
			return true;
		}
		return false;
	}
	return false;
}
function toggle_text(ele, elename, eletext, flag)
{
	var curtext = $.trim($(ele+'[name="'+elename+'"]').val());
	if (curtext == eletext && flag == 1) 
	{
		$(ele+'[name="'+elename+'"]').val('');
	}
	else if ((curtext == "" || curtext == null) && flag == 0) 
	{
		$(ele+'[name="'+elename+'"]').val(eletext);
	}
}
function toggle_elm(id) 
{
	$(id).toggle();
}
function answer_color(id) //This is for changing color on answer selection
{
	$('.cls_ans').css('backgroundColor', '#ffffff');
	$('.cls_ans').css('color', '#000000');
	$('#answer_td'+id).css('backgroundColor', '#090');
	$('#answer_td'+id).css('color', '#ffffff');
	CKEDITOR.instances['cexplanation'].setData("Hence, ["+id+"]")
}
function answer_color3plus(i,d) //This is for changing color on answer selection Blank 3+3+3 or 3+3
{
	$('.cls_ans').css('backgroundColor', '#ffffff');
	$('.cls_ans').css('color', '#000000');
	$('#answer_td'+i+d).css('backgroundColor', '#090');
	$('#answer_td'+i+d).css('color', '#ffffff');
}
function answer_color1(i,d) //This is for changing color on answer selection (Block 1,Block 2,...)
{
	$('.cls_ans'+i).css('backgroundColor', '#ffffff');
	$('.cls_ans'+i).css('color', '#000000');
	$('#answer_td'+i+d).css('backgroundColor', '#090');
	$('#answer_td'+i+d).css('color', '#ffffff');
}
function get_section1(flag)
{
	var topic 		= $.trim($('select[name="topic"]').val());
	var section 	= $.trim($('select[name="section"]').val());
	var subsection 	= $.trim($('select[name="subsection"]').val());
	var area 		= $.trim($('select[name="area"]').val());
	var qtopics 	= $.trim($('select[name="qtopics"]').val());
	var format 		= $.trim($('select[name="format1"]').val());

	if (topic == "" || topic == null) 
	{
		$('select[name="section"]').html('<option value="">Select Section</option>');
		$('select[name="subsection"]').html('<option value="">Select SubSection</option>');
		$('select[name="area"]').html('<option value="">Select Area</option>');
		$('select[name="qtopics"]').html('<option value="">Select Qtopics</option>');
		$('select[name="format1"]').html('<option value="">Select Format</option>');
	} 
	else 
	{
		$.post(location.href,{topic:topic,section:section, subsection:subsection, area:area, format:format, qtopics:qtopics, flag:flag, jsubmit:'Get_Section'},function(data){
			var json_string = JSON.parse(data);
			$('select[name="section"]').html(json_string.section);
			$('select[name="subsection"]').html(json_string.subsection);
			$('select[name="area"]').html(json_string.area);
			$('select[name="qtopics"]').html(json_string.qtopics);
			$('select[name="format1"]').html(json_string.format);
			get_options();
			//get_options_setdata(optvals);
		});
	}
}

function chk_answer_color(id)
{
	if($('#chk'+id).is(":checked"))
	{
		$('#answer_td'+id).css('backgroundColor', '#090');
		$('#answer_td'+id).css('color', '#ffffff');
	}
	else
	{
		$('#answer_td'+id).css('backgroundColor', '#ffffff');
		$('#answer_td'+id).css('color', '#000000');
	}
}
