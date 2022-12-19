{* HEADER *}

<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="top"}
</div>
<h2><ts>Setup for automatical mailings</ts></h2>
<div id="help">{ts}<b>Step 1:</b> Select and save a template at this page.
            <br>
            <b>Step 2:</b> You need to configure CiviCRM's "Scheduled Jobs" in order to send birthday messages
        automatically.
    This can be found in "Administration Console". Please select the "Birthdays" API call in combination with
                           "sendGreetings" and fill in details and an execution time of this job.<br>
  {/ts}</div>
<br>
<h2><ts>Testing email notifications</ts></h2>
<div id="help">{ts}
        <b>Peparation:</b> To test it you can add yourself to the birthday greeting group. Set your birthday to today
                           .<br>
        Make sure to previously set and save a template at this page.
        <br>
        <b>Execution:</b> go to "Scheduled Jobs" and execute the tasks by clicking "Execute Now"
        <br>
        <b>Goal:</b> You now should receive an email with birthday greetings
  {/ts}</div>
<br>

<h2><ts>Template selection</ts></h2>
<div id="help">{ts}Please select a template for birthday greeting emails{/ts}</div>
{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}
{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
