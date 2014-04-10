<table class="table_billing" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td width="50%" valign="top">
            <fieldset class="adminform">
                <legend>{billing_address_information_lbl}</legend>
                {billing_address}<br />{edit_billing_address}
            </fieldset>
        </td>

        <td width="50%" valign="top">
            <fieldset class="adminform">
                <legend>{shipping_address_information_lbl}</legend>
                {shipping_address}
            </fieldset>
        </td>
    </tr>
    <tr>
        <td colspan="2">{shippingbox_template:shipping_box}</td>
    </tr>
    <tr>
        <td valign="top">{shipping_template:shipping_method}</td>
        <td valign="top">{payment_template:payment_method}</td>
    </tr>

    </tbody>
</table>
{checkout_template:checkout}