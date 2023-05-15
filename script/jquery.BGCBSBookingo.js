/******************************************************************************/
/******************************************************************************/

;(function($,doc,win)
{
	"use strict";
	
	var BGCBSBookingo=function(object,option)
	{
		/**********************************************************************/
		
        var $self=this;
		var $this=$(object);
		
		var $optionDefault;
		var $option=$.extend($optionDefault,option);
        
        /**********************************************************************/
        
        this.setup=function()
        {
            $self.init();
        };
            
        /**********************************************************************/
        
        this.init=function()
        {
			$self.createTab();
			
			$self.createPromoSection();
			
			$self.createtField();
			
			$self.createSelectField();
			
			$self.createCheckboxField();
			
			$self.createPayment();
			
			$self.sendBooking();
			
			$self.payBooking()
			
			$self.applyCoupon();
			
			$self.createParticipantNumber();
			
			$self.setWidthClass();
            
			$this.removeClass('bgcbs-hidden');
        };
		
		/**********************************************************************/
		
		this.createTab=function()
		{
			$self.e('.ui-tabs').tabs({active:$option.tab_active});
			
			$('.bgcbs-main').on('click','.bgcbs-info-2-section .bgcbs-button',function(e)
			{
				e.preventDefault();
				$self.e('.ui-tabs').tabs({'active':1});
				$.scrollTo($self.e('.ui-tabs'));
			});
		};
		
		/**********************************************************************/
		
		this.createPromoSection=function()
		{
			$('.bgcbs-main').on('click','.bgcbs-course-promo-section.bgcbs-course-promo-section-video>div:first',function()
			{
				$(this).parents('.bgcbs-course-promo-section').children('div:first').addClass('bgcbs-hidden');
				$(this).parents('.bgcbs-course-promo-section').children('div:first+div').removeClass('bgcbs-hidden');
			});
		};
		
		/**********************************************************************/
		
		this.createParticipantNumber=function()
		{
			$('.bgcbs-participant-number-circle').waypoint(function()
			{
				$(this).circleProgress( 
				{
					startAngle:-Math.PI/2,
					size:50,
					lineCap:'round',
					fill:$(this).find('b:first').css('border-top-color'),
					emptyFill:$(this).find('b:first+b').css('border-top-color'),
					thickness:2,
					animation:{duration:5000}
				}).on('circle-animation-progress',function(event,progress,stepValue) 
				{
					var value=stepValue*100;
					value=value.toFixed(0);
					$(this).find('div').text(value+'%');
				});
			},
			{
				offset:'90%',
				triggerOnce:true 
			});			
		};
		
		/**********************************************************************/
		
		this.createtField=function()
		{        
			$('.bgcbs-main').on('click','.bgcbs-form-field',function()
			{
                var select=$(this).find('select');
                
                if(select.length) select.selectmenu('open');  
                else $(this).find(':input').focus();
			});
			
			$('.bgcbs-main').on('blur','[name="bgcbs_participant_first_name"]',function()
			{
				var helper=new BGCBSHelper();
				var applicatFirstName=$self.e('[name="bgcbs_applicant_first_name"]');
				
				if(parseInt(applicatFirstName.length,10)===1)
				{
					if(helper.isEmpty(applicatFirstName.val()))
						applicatFirstName.val($(this).val());
				}
			});
			
			$('.bgcbs-main').on('blur','[name="bgcbs_participant_second_name"]',function()
			{
				var helper=new BGCBSHelper();
				var applicatSecondName=$self.e('[name="bgcbs_applicant_second_name"]');
				
				if(parseInt(applicatSecondName.length,10)===1)
				{
					if(helper.isEmpty(applicatSecondName.val()))
						applicatSecondName.val($(this).val());
				}
			});
		};
		
		/**********************************************************************/
		
        this.createSelectField=function()
        {
            $self.e('select').selectmenu(
            {
                appendTo:$this,
				open:function(event,ui)
				{
					$('.qtip').remove();
                    
					var select=$(this);
                    
                    var selectmenu=$('#'+select.attr('id')+'-menu').parent('div');
                    
                    var field=select.parents('.bgcbs-form-field:first');
                    
                    var borderWidth=parseInt(field.css('border-left-width'),10)+parseInt(field.css('border-right-width'),10);
                    
                    var width=field[0].getBoundingClientRect().width-borderWidth;
                    
                    selectmenu.css({width:width+2});
				},
				change:function(event,ui)
				{
                    var name=$(this).attr('name');
                    
                    if(name==='bgcbs_course_group_id')
                    {
                        $self.changeGroupAjax();
                    }
				}
			});
            
            $self.e('select').parent('.bgcbs-form-field').addClass('bgcbs-form-field-type-select');
			
			$self.e('.ui-selectmenu-button .ui-icon.ui-icon-triangle-1-s').attr('class','bgcbs-icon-meta-24-chevron-vertical'); 
		};
		
		/**********************************************************************/
		
		this.createPayment=function()
		{
			$('.bgcbs-payment-form').on('click','li',function()
			{				
				$(this).parent('ul').children('li').removeClass('bgcbs-state-select');
				$(this).addClass('bgcbs-state-select');
			});
		};
		
		/**********************************************************************/
		
		this.createCheckboxField=function()
		{
			$self.e('>*').on('click','.bgcbs-form-checkbox-field.bgcbs-form-checkbox-field-style-1',function(e)
			{
				$self.handleCheckboxField($(this).children('span'));
			});
			
            $self.e('>*').on('click','.bgcbs-form-checkbox-field.bgcbs-form-checkbox-field-style-2>span',function()
            {
				$self.handleCheckboxField($(this));
            });	
		};
		
		/**********************************************************************/
		
		this.handleCheckboxField=function(object)
		{
			var field=object.nextAll('input[type="hidden"]');

			var value=parseInt(field.val())===1 ? 0 : 1;

			if(value===1) 
			{	
				object.addClass('bgcbs-icon-meta-16-check');
				object.parent('.bgcbs-form-checkbox-field').addClass('bgcbs-state-select');
			}
			else 
			{	
				object.removeClass('bgcbs-icon-meta-16-check');
				object.parent('.bgcbs-form-checkbox-field').removeClass('bgcbs-state-select');
			}

			field.val(value).trigger('change');			
		};
		
		/**********************************************************************/
		
		this.sendBooking=function()
		{
			$self.e('#bgcbs_step_1').on('click','.bgcbs-button.bgcbs-button-send-booking>a',function(e)
			{
				e.preventDefault();
				
				$self.clearNotice();
				
				$self.preloader(true);
				
				$self.setAction('send_booking');
				
				$self.post($self.e('form[name="bgcbs-form"]').serialize(),function(response)
				{
					if(typeof(response.error)!=='undefined')
					{
						if(response.error.global.length)
						{
							$self.e('#bgcbs_step_1 .bgcbs-notice').removeClass('bgcbs-hidden').html(response.error.global[0].message);
							$self.scrollAfterError(1);
						}
						
						if(response.error.local.length)
						{
							for(var index in response.error.local)
							{
								var selector='[name="'+response.error.local[index].field+'"]:eq(0)';

								var object=$self.e(selector).prevAll('label');
								if(parseInt(object.length,10)===0) object=$self.e(selector).parents('*').prevAll('label:first');

								object.qtip(
								{
									show:
									{ 
										target:$(this) 
									},
									style:	
									{ 
										classes:(response.error===1 ? 'bgcbs-qtip bgcbs-qtip-error':'bgcbs-qtip bgcbs-qtip-success')
									},
									content:
									{ 
										text:response.error.local[index].message 
									},
									position:
									{ 
										my:($option.is_rtl ? 'bottom right' : 'bottom left'),
										at:($option.is_rtl ? 'top right' : 'top left'),
										container:object.parent()
									}
								}).qtip('show');	
							}
							
							$self.scrollAfterError(1);
						}
					}
					
					if(typeof(response.booking_id)!=='undefined')
					{
						if(typeof(response.redirect_url)!=='undefined')
						{
							window.location=response.redirect_url;
							return;
						}
						
						$self.e('#bgcbs_step_2').find('.bgcbs-button.bgcbs-button-pay-booking>a>span').html(response.value_gross_format);

						$self.e('#bgcbs_step_1').addClass('bgcbs-hidden');
						$self.e('#bgcbs_step_2').removeClass('bgcbs-hidden');

						$self.e('[name="bgcbs_booking_id"]').val(response.booking_id);	
						
						$.scrollTo($self.e('.bgcbs-payment-form'));
					}
		
					$self.preloader(false);
				});
			});
		};
		
		/**********************************************************************/
		
		this.payBooking=function()
		{
			$self.e('#bgcbs_step_2').on('click','.bgcbs-button.bgcbs-button-pay-booking>a',function(e)
			{
				e.preventDefault();

				$self.clearNotice();

				$self.setAction('pay_booking');
				
				var paymentId=$self.getPaymentId();

				var data=$self.e('form[name="bgcbs-form"]').serialize()+'&bgcbs_payment_id='+paymentId;

				$self.preloader(true);

				$self.post(data,function(response)
				{
					if((typeof(response.error)!=='undefined') && (response.error.global.length))
					{
						$self.e('#bgcbs_step_2 .bgcbs-notice').removeClass('bgcbs-hidden').html(response.error.global[0].message);	
						$self.scrollAfterError(2);
					}
					else
					{
						switch(parseInt(response.payment_type,10))
						{							
							case 1:

								if(typeof(response.payment_custom_success_url_address)!=='undefined')
									window.location.href=response.payment_custom_success_url_address;
								else window.location.reload();

							break;

							case 2:

								$.getScript('https://js.stripe.com/v3/',function() 
								{								
									var stripe=Stripe(response.stripe_api_key_publishable);

									stripe.redirectToCheckout(
									{
										sessionId:response.stripe_session_id
									}).then(function(result) 
									{

									});
								});

							break;

							case 3:

								$self.e('#bgcbs_step_2>.bgcbs-payment-form').append(response.paypal_form);
								$self.e('#bgcbs_step_2>.bgcbs-payment-form form').submit();

							break;
						}
					}

					$self.preloader(false);
				});
			});
		};
		
		/**********************************************************************/
		
		this.applyCoupon=function()
		{
			$self.e('#bgcbs_step_1').on('click','.bgcbs-button.bgcbs-button-apply-coupon>a',function(e)
			{
				e.preventDefault();
				
				$self.setAction('apply_coupon');

				$self.preloader(true);

				$self.post($self.e('form[name="bgcbs-form"]').serialize(),function(response)
				{	
					var object=$self.e('[name="bgcbs_coupon_code"]');

					object.prevAll('label').qtip(
					{
						show:
						{ 
							target:$(this) 
						},
						style:
						{ 
							classes:(response.error===1 ? 'bgcbs-qtip bgcbs-qtip-error' : 'bgcbs-qtip bgcbs-qtip-success')
						},
						content:
						{ 
							text:response.message
						},
						position:
						{ 
							my:($option.is_rtl ? 'bottom right' : 'bottom left'),
							at:($option.is_rtl ? 'top right' : 'top left'),
							container:object.parent()
						}
					}).qtip('show');	
					
					if(response.error===0)
					{
						$self.e('.bgcbs-main-content-bottom-right .bgcbs-info-2-section').remove();
						$self.e('.bgcbs-main-content-bottom-right').prepend(response.info_2_section);
					}
					
					$self.preloader(false)
				});
			});
		};
		
		/**********************************************************************/
		
		this.changeGroupAjax=function()
		{
			$self.setAction('change_group');
					
			$self.preloader(true);

			$self.post($self.e('form[name="bgcbs-form"]').serialize(),function(response)
			{	
				$self.e('.bgcbs-main-content-top .bgcbs-info-1-section').remove();
				$self.e('.bgcbs-main-content-bottom-right .bgcbs-info-2-section').remove();
				$self.e('.bgcbs-main-content-bottom-right .bgcbs-group-schedule-section').remove();

				if(typeof(response.info_1_section)!=='undefined')
				{
					$self.e('.bgcbs-main-content-top').append(response.info_1_section);
				}
				if(typeof(response.info_2_section)!=='undefined')
				{
					$self.e('.bgcbs-main-content-bottom-right').append(response.info_2_section);
					$self.createParticipantNumber();
				}
				if(typeof(response.schedule_section)!=='undefined')
				{
					$self.e('.bgcbs-main-content-bottom-right').append(response.schedule_section);
				}
                
				$self.preloader(false);
			});		
		};
		
		/**********************************************************************/
		
		this.setWidthClass=function()
		{
			var width=$this.parent().width();
			
			var className=null;
			var classPrefix='bgcbs-width-';
			
			if(width>=1270) className='1270';
			else if(width>=940) className='960';
			else if(width>=750) className='768';
			else if(width>=460) className='480';
			else if(width>=300) className='300';
            else className='300';
			
			var oldClassName=$self.getValueFromClass($this,classPrefix);
			if(oldClassName!==false) $this.removeClass(classPrefix+oldClassName);
			
			$this.addClass(classPrefix+className);
			
			if($self.prevWidth!==width)
            {
				$self.prevWidth=width;
                $(window).resize();
            };
                        
			setTimeout($self.setWidthClass,500);
		};
        
		/**********************************************************************/
		
		this.getValueFromClass=function(object,pattern)
		{
			try
			{
				var reg=new RegExp(pattern);
				var className=$(object).attr('class').split(' ');

				for(var i in className)
				{
					if(reg.test(className[i]))
						return(className[i].substring(pattern.length));
				}
			}
			catch(e) {}

			return(false);		
		};
		
		/**********************************************************************/
		
		this.post=function(data,callback)
		{
			$.post($option.ajax_url,data,function(response)
			{ 
				callback(response); 
			},'json');
		}; 
		
		/**********************************************************************/
		
        this.preloader=function(action)
        {
            $('#bgcbs-preloader').css('display',(action ? 'block' : 'none'));
        };
		
		/**********************************************************************/
		
        this.setAction=function(name)
        {
            $self.e('input[name="action"]').val('bgcbs_'+name);
        };
		
		/**********************************************************************/
		
		this.getPaymentId=function()
		{
			var paymentId=parseInt($('.bgcbs-payment-form>ul>li.bgcbs-state-select').attr('data-payment_id'),10);
			return(paymentId);
		};
		
		/**********************************************************************/
		
        this.e=function(selector)
        {
            return($this.find(selector));
        };
		
		/**********************************************************************/
		
		this.clearNotice=function()
		{
			$('.qtip').remove();
			$self.e('.bgcbs-notice').addClass('bgcbs-hidden');			
		};
		
		/**********************************************************************/
		
		this.scrollAfterError=function(step)
		{
			if(parseInt(step,10)===1)
				$.scrollTo($self.e('.ui-tabs'));
			if(parseInt(step,10)===2)
				$.scrollTo($self.e('#bgcbs_step_2'))
		};

		/**********************************************************************/
        /**********************************************************************/
	};
	
	/**************************************************************************/
	
	$.fn.BGCBSBookingo=function(option) 
	{
        console.log('--------------------------------------------------------------------------------------------');
        console.log('Bookingo - Course Booking System for WordPress ver. '+option.plugin_version);
        console.log('http://quanticalabs.com');
        console.log('--------------------------------------------------------------------------------------------');
        
		var form=new BGCBSBookingo(this,option);
        return(form);
	};
	
	/**************************************************************************/

})(jQuery,document,window);

/******************************************************************************/
/******************************************************************************/