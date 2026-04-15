<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New Peak Experience Enquiry</title>
</head>
<body style="margin:0; padding:24px; background:#f4f1eb; color:#273243; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:720px; margin:0 auto; background:#ffffff; border:1px solid #ddd8cf; border-radius:16px; padding:24px;">
        <p style="margin:0 0 20px; font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#1f5c4d;">New Enquiry</p>
        <h1 style="margin:0 0 20px; font-size:28px; line-height:1.1;">Peak Experience enquiry submission</h1>

        <table role="presentation" style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; width:180px; font-weight:700;">Name</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['name'] }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; font-weight:700;">Organization</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['organization'] }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; font-weight:700;">Email</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['email'] }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; font-weight:700;">Phone</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['phone'] }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; font-weight:700;">Date of Event</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['date_of_event'] }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; font-weight:700;">Venue</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['venue'] }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; font-weight:700;">Number of Guests</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['guest_count'] !== '' ? $enquiry['guest_count'] : 'Not provided' }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0; border-top:1px solid #ece6dc; font-weight:700;">Type of Event</td>
                <td style="padding:10px 0; border-top:1px solid #ece6dc;">{{ $enquiry['event_type'] }}</td>
            </tr>
        </table>

        <div style="margin-top:24px; padding-top:20px; border-top:1px solid #ece6dc;">
            <p style="margin:0 0 10px; font-weight:700;">Additional Information</p>
            <p style="margin:0; white-space:pre-line; line-height:1.7;">{{ $enquiry['additional_info'] !== '' ? $enquiry['additional_info'] : 'No additional information provided.' }}</p>
        </div>
    </div>
</body>
</html>
