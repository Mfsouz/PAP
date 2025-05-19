<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>8Bit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .game-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin: 15px;
            background-color: #fff;
            height: 97%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .game-card img {
            width: 100%;
            height: 450px;
            /* Define altura fixa */
            object-fit: cover;
            /* Corta e ajusta proporcionalmente */
            border-radius: 8px;
        }

        .game-card h3 {
            font-size: 1.5rem;
            margin-top: 10px;
            flex-grow: 1;
            /* Expande o título para ocupar espaço */
        }

        .game-card .btn-buy {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .game-card .btn-buy:hover {
            background-color: #0056b3;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 20px;
        }

        .modal-footer {
            display: flex;
            flex-shrink: 0;
            padding: calc(var(--bs-modal-padding) - var(--bs-modal-footer-gap) * .5);
            background-color: var(--bs-modal-footer-bg);
            border-top: var(--bs-modal-footer-border-width) solid #007fff;
            border-bottom-right-radius: var(--bs-modal-inner-border-radius);
            border-bottom-left-radius: var(--bs-modal-inner-border-radius);
            justify-content: center;
            align-items: flex-start;
            align-content: flex-end;
            flex-direction: row;
        }

        .modal {
    --bs-modal-zindex: 1055;
    --bs-modal-width: 500px;
    --bs-modal-padding: 1rem;
    --bs-modal-margin: 0.5rem;
    --bs-modal-color: ;
    --bs-modal-bg: #fff;
    --bs-modal-border-color: var(--bs-border-color-translucent);
    --bs-modal-border-width: 1px;
    --bs-modal-border-radius: 0.5rem;
    --bs-modal-box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --bs-modal-inner-border-radius: calc(0.5rem - 1px);
    --bs-modal-header-padding-x: 1rem;
    --bs-modal-header-padding-y: 1rem;
    --bs-modal-header-padding: 1rem 1rem;
    --bs-modal-header-border-color: #007fff;        
    --bs-modal-header-border-width: 1px;
    --bs-modal-title-line-height: 1.5;
    --bs-modal-footer-gap: 0.5rem;
    --bs-modal-footer-bg: ;
    --bs-modal-footer-border-color: var(--bs-border-color);
    --bs-modal-footer-border-width: 1px;
    position: fixed;
    top: 0;
    left: 0;
    z-index: var(--bs-modal-zindex);
    display: none;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
}

    </style>
</head>