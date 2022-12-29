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
        automatically via e-mail.{/ts}
    <br>
    {ts}Please note: With default settings no email will be sent after upgrading to this verison. Mails only will be
        sent if triggering an APIv3 or APIv4 birthday action manually or using scheduled jobs{/ts}</div>
<br>
<h2>
    <ts>Setup for automatical mailings</ts>
</h2>
<div id="help">{ts}
        <b>{ts}Step 1:{/ts}<br></b>
    {ts}Create a message template (see description below).{/ts}<br>
        <b>{ts}Step 2:{/ts}<br></b>
    {ts}Select and save this template using this settings page.{/ts}
        <br>
        <b>{ts}Step 3:{/ts}<br></b>
    {ts}You need to configure CiviCRM's "Scheduled Jobs" (
        <code>/civicrm/admin/job</code>
        ) in order to send birthday
        messages
        automatically.{/ts}
        <br>
    {ts}This can be found in "Administration Console". Please select the "Birthdays" API call in combination with
        "sendgreetings" (lowercase) and select the "daily" execution time of this job. "Command parameters" field
        can be
        left
        empty.{/ts}
        <br>
    {/ts}</div>
<br>
<h2>
    <ts>Testing email notifications</ts>
</h2>
<div id="help">
        <b>{ts}Peparation:{/ts}</b><br>
        {ts}For testing you can add yourself to the birthday greeting group. Set your birthday to today.{/ts}
        <br>
    {ts}Make sure to previously set and save a template at this page.{/ts}
        <br>
        <b>{ts}Execution:{/ts}</b><br>
    {ts}Go to "Scheduled Jobs" and execute the tasks by clicking "Execute Now"{/ts}
        <br>
        <b>{ts}Goal:{/ts}</b><br>
    {ts}You now should receive an email with birthday greetings{/ts}
</div>
<br>

<h2>
    <ts>Template selection</ts>
</h2>
<div id="help">
    {ts}Please select a template for birthday greeting emails{/ts}<br>
    {ts}Those can be found in:{/ts}<br>
    <ul>
        <li>{ts}Navigating to this URL: <code>/civicrm/admin/messageTemplates</code>{/ts}</li>
        <li>{ts}Click path: Topbar --> "Mailings" --> "Message Templates"{/ts}</li>
    </ul>
</div>


<div class="crm-section">
    <div class="label">{$form.message_template_id.label}</div>
    <div class="content">{$form.message_template_id.html}</div>
    <div class="clear"></div>
</div>

<br>

<h2>
    <ts>Sender email selection</ts>
</h2>
<div id="help">{ts}Please select an outgoing email address for birthday greeting emails{/ts}
    <br>
    {ts}Those email addressed can be added using the "Administration Console" --> "From Email Address Options"{/ts}
    <br>{ts}Path:{/ts} <code>/civicrm/admin/options/from_email_address</code></div>
<div class="crm-section">
    <div class="label">{$form.birthday_sender_email_address_id.label}</div>
    <div class="content">{$form.birthday_sender_email_address_id.html}</div>
    <div class="clear"></div>
</div>

<br>

<h2>
    <ts>Interesting facts about filter options</ts>
</h2>
<div id="help"><b>{ts}The contact filter currently filters contacts with:{/ts}</b><br>
    <ul> <!-- Please sync sql query documentation in CRM/Birthdays/BirthdayContacts.php
            whenever you add or change things here-->
        <li>{ts}contacts which are part of the programmatically prepared birthday group{/ts}</li>
        <li>{ts}contacts with a birthday which is equal with the date of execution (unless debug mode is set - see
                below)
            {/ts}</li>
        <li><code>contact_type</code> = 'Individual'</li>
        <li><code>opt_out</code> = 0</li>
        <li><code>do_not_email</code> = 0</li>
        <li>{ts}Contact has a primary email address set{/ts}</li>
    </ul>
</div>
    <br>
    <h2>
        <ts>Test / debug mode (!! Sysadmins only !!))</ts>
    </h2>
<div id="help"><b>{ts}A debug mode can be set programatically:{/ts}</b><br>
        <p>{ts}Navigate to:{/ts} <code>/de.systopia.birthdays/CRM/Birthdays/BirthdayContacts.php</code></p>
        <p>{ts}A debug email can be set there which leads to:{/ts}</p>
        <ul> <!-- Please sync this documentation with mentioned php class path-->
            <li>{ts}writing of successful and failed activities will be suppressed{/ts}</li>
            <li>{ts}all emails will be redirected to this email set there ( <code>$is_debug_email =
                    'all_mails_to@thisdomain.com';</code> ){/ts}</li>
            <li>{ts}day filter is de-activated which selects up to 10 mails where a birth date is set{/ts}</li>
        </ul>
    {ts}Set <code>$is_debug_email = '';</code> to de-aktivate debug mode.{/ts}
</div>


{* FOOTER *}
<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
