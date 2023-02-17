{*-------------------------------------------------------+
| SYSTOPIA Birthday Tools                                |
| Copyright (C) 2023 SYSTOPIA                            |
| Author: J. Franz (franz@systopia.de)                   |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+-------------------------------------------------------*}

{crmScope extensionKey='de.systopia.birthdays'}
    <h1>
        {ts}General{/ts}
    </h1>
    <div id="help">{ts}This open source extension
            (
            <a href="https://github.com/systopia/de.systopia.birthdays#readme">de.systopia.birthdays</a>
            )
            allows you to build a report on upcoming birthdays and provides an API for sending birthday
            greetings automatically via e-mail.{/ts}
        <br>
        {ts}Please note: With default settings no email will be sent after upgrading to this version.
            Mails only will be sent if triggering an APIv3 or APIv4 birthday action manually or using scheduled jobs.{/ts}
    </div>
    <br>
    <h1>
        {ts}Updated extension?{/ts}
    </h1>
    <div id="help">
        {ts}If you updated from an older version chances are you need to run a migration in your{/ts}
        <a href="{crmURL p='civicrm/admin/extensions' q='reset=1'}">{ts}extension directory{/ts}</a>.
    </div>
    <br>
    <hr>
    <br>
    <h1>
        {ts}Step 1: Provide an email message template{/ts}
    </h1>
    <div id="help">
        {ts}Those can be found in:{/ts}<br>
        {ts}Topbar --> "Mailings" -->{/ts} "<a
                href="{crmURL p='civicrm/admin/messageTemplates' q='reset=1'}">{ts}MessageTemplates{/ts}</a>"
        <br>
        <br>
        {ts}Tokens can be used here if SMARTY is enabled.{/ts}
    </div>
    <br>
    <div class="crm-block crm-form-block">
        <h1>
            {ts}Step 2: Select a template{/ts}
        </h1>
        <div id="help">
            {ts}Please select a previously created or edited template for birthday greeting emails.
                <br>
                Make sure to reload this page if your template is not listed here. Templates need to be activated before.{/ts}
            <br>
        </div>
        <div class="crm-section">
            <div class="label">{$form.message_template_id.label}</div>
            <div class="content">{$form.message_template_id.html}</div>
            <div class="clear"></div>
        </div>
        {include file="CRM/common/formButtons.tpl" location="bottom"}

    </div>
    <br>
    <br>
    <br>
    <h1>
        {ts}Step 3: Sender email preparation{/ts}
    </h1>
    <div id="help">{ts}This step is optional if you are happy with the provided options in the upcomming section.{/ts}
        <br>
        <br>
        {ts}Those email addressed can be added using the topbar: "Administer" --> "Administration Console" -->{/ts}
        "<a href="{crmURL p='civicrm/admin/messageTemplates' q='reset=1'}">{ts}From Email Addresses{/ts}</a>"<br><br>
        {ts}Please talk to your sysadmin if there are additional steps needed in order to add emails there to avoid problems.{/ts}
        <br>
        {ts}Check if this is a noreply address or if you would like to monitor this email inbox.{/ts}
    </div>
    <br>
    <div class="crm-block crm-form-block">
        <h1>
            {ts}Step 4: Sender email selection{/ts}
        </h1>
        <div id="help">{ts}Please select an outgoing email address for birthday greeting emails.{/ts}<br>
            {ts}If this is empty please set up an email address as described in the previous section{/ts}
            <br>
        </div>
        <div class="crm-section">
            <div class="label">{$form.birthday_sender_email_address.label}</div>
            <div class="content">{$form.birthday_sender_email_address.html}</div>
            <div class="clear"></div>
        </div>
        {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
    <br>
    <br>
    <br>
    <h1>
        {ts}Step 5: Make sure to understand filter criteria{/ts}
    </h1>
    <div id="help"><b>{ts}The contact filter currently filters these contacts:{/ts}</b><br>
        <ul> <!-- Please sync sql query documentation in CRM/Birthdays/BirthdayContacts.php
            whenever you add or change things here-->
            <li>{ts}contacts which are part of the programmatically prepared birthday group{/ts}</li>
            <li>{ts}contacts with a birthday which is equal with the date of execution (unless debug mode is set - see below){/ts}</li>
            <li><code>contact_type</code> = 'Individual'</li>
            <li><code>opt_out</code> = 0</li>
            <li><code>do_not_email</code> = 0</li>
            <li>{ts}Contact has a primary email address set{/ts}</li>
        </ul>
    </div>
    <br>
    <h1>
        {ts}Step 6: Add some test contacts{/ts}
    </h1>
    <div id="help">
        {ts}Keep section "filter criteria" above in mind and add some test contacts to the birthday greeting group and set their birthday to today.{/ts}
        <br>
        {ts}Use the topbar to navigate to: "Contacts" -->{/ts}
        "<a href="{crmURL p='civicrm/group' q='reset=1'}">{ts}Manage Groups{/ts}</a>"<br>
        {ts}This group was programatically created by this extension by installation.{/ts}
    </div>
    <br>
    <h1>
        {ts}Step 7: Automation using scheduled jobs{/ts}
    </h1>
    <div id="help">
        {ts}Use the topbar to navigate to: "Administer" --> "Administration Console" -->{/ts} "
        <a href="{crmURL p='/civicrm/admin/job' q='reset=1'}">{ts}Scheduled Jobs{/ts}</a>".<br>
        {ts}Click button: "Add New Scheduled Job"{/ts}<br>
        {ts}Name for example: "Daily email birthday greetings"{/ts}<br>
        {ts}Run frequency: "Daily"{/ts}<br>
        {ts}Select "Birthdays" using the dropdown list{/ts}<br>
        {ts}Type "sendgreetings" (lowercase) into the textfield behind the dropdown list{/ts}<br>
        {ts}Leave "Command parameters" empty{/ts}<br>
        {ts}Scheduled Run Date: Can be left empty while testing.{/ts}<br>
        {ts}Is this Scheduled Job active?: Can be left de-activated while testing.{/ts}<br>
        {ts}Click: "Save"{/ts}<br>
        <b>{ts}Warning:{/ts}</b>
        {ts}Using scheduled jobs setting{/ts}
        <code>runInNonProductionEnvironment=TRUE</code>
        {ts}in a development{/ts} <a href="{crmURL p='/civicrm/admin/setting/smtp' q='reset=1'}">
            {ts}emails set to "redirected to database"{/ts}</a>
        {ts}will<u>send an actual email</u>skipping db redirection in some cases!{/ts}
    </div>
    <br>
    <h1>
        {ts}Step 8a: Testing it by using scheduled jobs{/ts}
    </h1>
    <div id="help">
        {ts}Check if you want to use the special debug mode described in the next chapter to avoid traces in
            your system.{/ts}
        <br>
        <br>
        {ts}Otherwise use the topbar to navigate to: "Administer" --> "Administration Console" -->{/ts} "
        <a href="{crmURL p='/civicrm/admin/job' q='reset=1'}">{ts}Scheduled Jobs{/ts}</a>".<br>
        {ts}Find the previous created birthday job in this list{/ts}<br>
        {ts}Click: "more"{/ts}<br>
        {ts}Click: "Execute Now" <b>(Warning: <u>Actual emails</u> will be sent to the contacts email addresses if
        everything works as expected!)</b>{/ts}<br>
        {ts}Your previously selected contacts should now receive an email with birthday greetings.{/ts}
        {ts}Make sure to check what type of activity description had been added to your selected contacts.{/ts}
    </div>
    <br>
    <br>
    <h1>
        {ts}Step 8b: Test it by using the API{/ts}
    </h1>
    <div id="help">
        <h2>{ts}Preparation{/ts}</h2>
        <p>{ts}Navigate to the {/ts}<a href="{crmURL p='/civicrm/api4' q=''}">APIv4 Explorer</a>
            {ts}in your "Administration" menu and select:{/ts}</p>
        <ul> <!-- Please sync this documentation with mentioned php class path-->
            <li>{ts}Birthdays as your Entity{/ts}</li>
            <li>{ts}sendGreetings as your Action{/ts}</li>
        </ul>
        <br>
        <h2>{ts}Parameters{/ts}</h2>
        <p>{ts}Currently there are two parameter available to set debug options.{/ts}</p>
        <br>
        <b><i>disable_acitivites</i> {ts}checkbox{/ts}</b><br>
        {ts}This allows to set these modes for this testing session:{/ts}
        <ul> <!-- Please sync this documentation with mentioned php class path-->
            <li>{ts}true: "successful" or "failed" activities will be suppressed{/ts}</li>
            <li>{ts}false: "successful" or "failed" activities will be added to contacts{/ts}</li>
        </ul>
        <br>
        <br>
        <b><i>debug_email</i> {ts}text field{/ts}</b><br>
        {ts}Please add a real email address here. It will only be used for this testing session.{/ts}
        {ts}For example set 'all-birthday-mails-go-to@this-domain.com' as your test email.{/ts}
        <p>{ts}Setting an address here changes the extensions behavior to:{/ts}</p>
        <ul> <!-- Please sync this documentation with mentioned php class path-->
            <li>{ts}All emails will be redirected to this email set above{/ts}</li>
            <li>{ts}The previous described filter is de-activated{/ts}</li>
            <li>{ts}10 contacts/mails will be selected where a birthdate is set{/ts}</li>
        </ul>
        <br>
    </div>
    <br>
    <h1>
        {ts}Step 9: Final activation{/ts}
    </h1>
    <div id="help">
        <p>{ts}If previous tests were successful we can now activate it for automated sending:{/ts}</p>
        <ul>
            <li>{ts}Again use the topbar to navigate to: "Administer" --> "Administration Console" -->{/ts} "<a
                        href="{crmURL p='/civicrm/admin/job' q='reset=1'}">{ts}Scheduled Jobs{/ts}</a>".
            </li>
            <li>{ts}Again find the previous created birthday job in this list{/ts}</li>
            <li>{ts}Again click: "more"{/ts}</li>
            <li>{ts}Is this Scheduled Job active?: Set a first execution date and time.{/ts}</li>
            <li>{ts}Click: "Save"{/ts}</li>
            <li>{ts}Again find the previous created birthday job in this list{/ts}</li>
            <li>{ts}Again click: "more"{/ts}</li>
            <li>{ts}Click: "Enable"{/ts}</li>
        </ul>
        {ts}This enables the daily sending of birthday mails for your instance.{/ts}
    </div>
    <br>
    <hr>
    <br>
    <h1>
        {ts}Extension development{/ts}
    </h1>
    <div id="help">
        {ts}If you bring development skills feel free to{/ts}
        <a href="https://github.com/systopia/de.systopia.birthdays/pulls">{ts}leave a pull request{/ts}</a>
        {ts}and extend this open source extension or contact{/ts}
        <a href="mailto:info@systopia.de?subject=Birthdays%Extension">info@systopia.de</a>
        {ts}for contract work.{/ts}
        {ts}Bugs can be reported to {/ts}<a href="https://github.com/systopia/de.systopia.birthdays/issues">
            {ts}using GitHubs issue list{/ts}</a> {ts}if they aren't listed there already.{/ts}
    </div>
{/crmScope}