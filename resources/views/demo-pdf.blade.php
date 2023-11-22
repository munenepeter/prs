<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('img/gmk.png') }}" sizes="16x16 32x32" type="image/png">
    <title>Daily Report</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

    :root {
        --font-color: black;
        --highlight-color: #60D0E4;
        --header-bg-color: #B8E6F1;
        --footer-bg-color: #BFC0C3;
        --table-row-separator-color: #BFC0C3;
    }

    @page {

        size: A4;
        margin: 8cm 0 3cm 0;

        @top-left {
            content: element(header);
        }

        @bottom-left {
            content: element(footer);
        }
    }

    body {
        margin: 0;
        padding: 1cm 2cm;
        color: var(--font-color);
        font-family: 'Montserrat', sans-serif;
        font-size: 10pt;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    hr {
        margin: 1cm 0;
        height: 0;
        border: 0;
        border-top: 1mm solid var(--highlight-color);
    }

    header {
        height: 6cm;
        padding: 0 2cm;
        position: running(header);
        background-color: var(--header-bg-color);
    }

    header .headerSection {
        display: flex;
        justify-content: space-between;
    }

    header .headerSection:first-child {
        padding-top: .5cm;
    }

    header .headerSection:last-child {
        padding-bottom: .5cm;
    }

    header .headerSection div:last-child {
        width: 35%;
    }

    header .logoAndName {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    header .logoAndName svg {
        width: 1.5cm;
        height: 1.5cm;
        margin-right: .5cm;
    }

    header .headerSection .invoiceDetails {
        padding-top: .5cm;
    }

    header .headerSection h3 {
        margin: 0 .75cm 0 0;
        color: var(--highlight-color);
    }

    header .headerSection div p {
        margin-top: 2px;
    }

    header h1,
    header h2,
    header h3,
    header p {
        margin: 0;
    }

    header .invoiceDetails,
    header .invoiceDetails h2 {
        text-align: right;
        font-size: 1em;
        text-transform: none;
    }

    header h2,
    header h3 {
        text-transform: uppercase;
    }

    header hr {
        margin: 1cm 0 .5cm 0;
    }

    main table {
        width: 100%;
        border-collapse: collapse;
    }

    main table thead th {
        height: 1cm;
        color: var(--highlight-color);
    }

    main table thead th:nth-of-type(2),
    main table thead th:nth-of-type(3),
    main table thead th:last-of-type {
        width: 2.5cm;
    }

    main table tbody td {
        padding: 2mm 0;
    }

    main table thead th:last-of-type,
    main table tbody td:last-of-type {
        text-align: right;
    }

    main table th {
        text-align: left;
    }

    main tfoot.summary {
        width: calc(40% + 2cm);
        margin-left: 60%;
        margin-top: .5cm;
    }

    main tfoot.summary tr.total {
        font-weight: bold;
        background-color: var(--highlight-color);
    }

    main tfoot.summary th {
        padding: 4mm 0 4mm 1cm;
    }

    main tfoot.summary td {
        padding: 4mm 2cm 4mm 0;
        border-bottom: 0;
    }
    footer {
        height: 3cm;
        line-height: 3cm;
        padding: 0 2cm;
        position: running(footer);
        background-color: var(--footer-bg-color);
        font-size: 8pt;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
    }

    footer a:first-child {
        font-weight: bold;
    }
</style>

<body>

    <header>
        <div class="headerSection">

            <div class="logoAndName">
                <a href="/">
                    <img src="{{ asset('img/gmk.png') }}" alt="GMK Brand Logo" width="50" height="20"
                        loading="lazy">
                </a>

                <h2>{{ config('app.name', 'Project Management System') }}</h2>
            </div>

            <div class="invoiceDetails">
                <h2>Page 1 of 1</h2>
                <p>
                    {{ date('d/m/Y') }}
                </p>
            </div>
        </div>

        <hr />
        <div class="headerSection">
            <div>
                <h3>Daily Report</h3>
                <p>
                    <b>username</b>
                </p>
            </div>

            <div>
                <h3>Due Date</h3>
                <p>
                    <b>07 April 2021</b>
                </p>
            </div>
            <div>
               <h3>Amount</h3>
                <p>
                    <b>$3,500</b>
                </p>
            </div>
        </div>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @for ($i = 0; $i < 5; $i++)
                    <tr>
                        <td>
                            <b>Item Names Goes Here</b>
                            <br />
                            Description goes here
                        </td>
                        <td>
                            $100
                        </td>
                        <td>
                            4
                        </td>
                        <td>
                            $400.00
                        </td>
                    </tr>
                @endfor
            </tbody>
            <tfoot class="summary">
                <tr class="total">
                    <td>
                        <b>Item Names Goes Here</b>
                    </td>
                    <td>
                        $100
                    </td>
                    <td>
                        4
                    </td>
                    <td>
                        $400.00
                    </td>
                </tr>
            </tfoot>
        </table>
    </main>




    <footer>
        <a href="https://companywebsite.com">
            companywebsite.com
        </a>
        <a href="mailto:company@website.com">
            company@website.com
        </a>
        <span>
            317.123.8765
        </span>
        <span>
            123 Alphabet Road, Suite 01, Indianapolis, IN 46260
        </span>
    </footer>

</body>

</html>
