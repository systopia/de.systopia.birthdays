{* HEADER *}

<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
</div>
<h2>
    <ts>General</ts>
</h2>
<div id="help">{ts}This extension (
        <a href="https://github.com/systopia/de.systopia.birthdays">de.systopia.birthdays</a>
        ) allows
        you to build a report on upcoming birthdays and provides an API for sending birthday greetings
        automatically via e-mail.{/ts}</div>
<br>
<h2>
    <ts>Setup for automatical mailings</ts>
</h2>
<div id="help">{ts}
        <b>Step 1:</b>
        Select and save a template at this page.
        <br>
        <b>Step 2:</b>
        You need to configure CiviCRM's "Scheduled Jobs" (
        <code>/civicrm/admin/job</code>
        ) in order to send birthday
        messages
        automatically.
        This can be found in "Administration Console". Please select the "Birthdays" API call in combination with
        "sendgreetings" (lowercase) and select the "daily" execution time of this job. "Command parameters" field
        can be
        left
        empty.
        <br>
    {/ts}</div>
<br>
<h2>
    <ts>Testing email notifications</ts>
</h2>
<div id="help">{ts}
        <b>Peparation:</b>
        To test it you can add yourself to the birthday greeting group. Set your birthday to today
        .
        <br>
        Make sure to previously set and save a template at this page.
        <br>
        <b>Execution:</b>
        go to "Scheduled Jobs" and execute the tasks by clicking "Execute Now"
        <br>
        <b>Goal:</b>
        You now should receive an email with birthday greetings
    {/ts}</div>
<br>

<h2>
    <ts>Template selection</ts>
</h2>
<div id="help">{ts}Please select a template for birthday greeting emails{/ts}</div>


<div class="crm-section">
    <div class="label">{$form.message_template_id.label}</div>
    <div class="content">{$form.message_template_id.html}</div>
    <div class="clear"></div>
</div>

<br>

<h2>
    <ts>Sender email selection</ts>
</h2>
<div id="help">{ts}Please select an outgoing email address for birthday greeting emails{/ts}</div>
<div class="crm-section">
    <div class="label">{$form.birthday_sender_email_address_id.label}</div>
    <div class="content">{$form.birthday_sender_email_address_id.html}</div>
    <div class="clear"></div>
</div>

<br>

<h2>
    <ts>Interesting facts</ts>
</h2>
<div id="help">{ts}There is a contact filter which only selects contacts with:{/ts}<br>
    <ul> <!-- Please sync sql query documentation in CRM/Birthdays/BirthdayContacts.php
            whenever you add or change things here-->
        <li>{ts}contacts which are part of the birthday group{/ts}</li>
        <li><code>contact_type</code> = 'Individual'</li>
        <li><code>opt_out</code> = 0</li>
        <li><code>do_not_email</code> = 0</li>
    </ul>
</div>

{* FOOTER *}
<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
