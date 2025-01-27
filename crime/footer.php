<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Police Case Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --police-blue: #1a3a85;
      --police-gold: #c4a777;
    }

    .footer {
      background: var(--police-blue);
      color: white;
      padding: 2rem 0;
      margin-top: 3rem;
      position: relative;
      box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.1);
    }

    .footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--police-gold);
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .footer-brand {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .footer-brand i {
      color: var(--police-gold);
      font-size: 2rem;
    }

    .footer-brand-text h4 {
      margin: 0;
      font-weight: 600;
      color: white;
    }

    .footer-brand-text p {
      margin: 0;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.7);
    }

    .footer-contact {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 15px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .contact-item:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    .contact-item i {
      color: var(--police-gold);
    }

    .contact-item span {
      color: rgba(255, 255, 255, 0.9);
      font-weight: 500;
    }

    .footer-bottom {
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.9rem;
    }

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 1rem;
      justify-content: center;
    }

    .social-link {
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      color: var(--police-gold);
      transition: all 0.3s ease;
    }

    .social-link:hover {
      background: var(--police-gold);
      color: var(--police-blue);
      transform: translateY(-2px);
    }

    @media (max-width: 768px) {
      .footer-content {
        flex-direction: column;
        text-align: center;
      }

      .footer-contact {
        flex-direction: column;
      }

      .footer-brand {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <i class="fas fa-shield-alt"></i>
          <div class="footer-brand-text">
            <h4>Police Case Management System</h4>
            <p>Serving & Protecting</p>
          </div>
        </div>
        <div class="footer-contact">
          <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>Emergency: 15</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>helpdesk@police.gov.pk</span>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Police Case Management System. All rights reserved.</p>
        <div class="social-links">
          <a href="#" class="social-link" title="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="social-link" title="Twitter">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" class="social-link" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="social-link" title="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>