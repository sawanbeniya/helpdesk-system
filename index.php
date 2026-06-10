<?php
session_start();

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    if ($role == 'admin') header("Location: admin/dashboard.php");
    elseif ($role == 'agent') header("Location: agent/dashboard.php");
    elseif ($role == 'enduser') header("Location: enduser/dashboard.php");
    else {
        session_destroy();
        header("Location: login.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HelpDesk System</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/helpdesk/assets/css/modern.css">

    <style>
        .section {
            padding: 60px 20px;
        }

        .section-light {
            background: #f8fafc;
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title h2 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .section-title p {
            color: #64748b;
            font-size: 14px;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            color: #fff;
        }

        .hero h1 {
            font-size: 36px;
        }

        .hero p {
            margin-top: 10px;
            opacity: 0.9;
        }

        .hero .btn {
            margin-top: 20px;
            background: #fff;
            color: #3b82f6;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            text-align: center;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
        }

        .help-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 45px;
    height: 45px;
    background: #3b82f6;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
}

.modal-content {
    background: #fff;
    padding: 25px;
    max-width: 400px;
    margin: 100px auto;
    border-radius: 12px;
}

.help-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 45px;
    height: 45px;
    background: #3b82f6;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    z-index: 1000;
}

/* PANEL */
.help-panel {
    position: fixed;
    top: 0;
    right: -350px;
    width: 320px;
    height: 100%;
    background: #ffffff;
    box-shadow: -6px 0 20px rgba(0,0,0,0.15);
    transition: 0.3s ease;
    z-index: 1001;
    border-left: 1px solid #e2e8f0;
}

/* OPEN STATE */
.help-panel.active {
    right: 0;
}

.help-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px;
    border-bottom: 1px solid #eee;
    font-size: 16px;
}

.help-header span {
    cursor: pointer;
    font-size: 20px;
}
.help-content {
    padding: 20px;
    font-size: 14px;
    line-height: 1.6;
}

.help-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(4px);
    display: none;
    z-index: 1000;
}

.help-overlay.active {
    display: block;
}

.profile-box {
    text-align: center;
    margin-bottom: 20px;
}

.avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #6366f1, #3b82f6);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 22px;
    font-weight: 600;
}

.profile-box h3 {
    margin: 5px 0;
}

.profile-box p {
    color: #64748b;
    font-size: 14px;
}

.info-section {
    margin-top: 20px;
}

.info-section h4 {
    margin-bottom: 8px;
    font-size: 14px;
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.tags span {
    background: #eef2ff;
    color: #3b82f6;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
}

    </style>
</head>

<body>

<!-- HERO -->
<div class="hero">
    <h1>Smart Help Desk System</h1>
    <p>Streamline support, boost productivity, and resolve tickets faster with a modern helpdesk system.</p>

    <div class="hero-buttons">
    <a href="login.php" class="btn btn-light">
        <i class="fa-solid fa-right-to-bracket"></i> Login
    </a>
    <a href="register.php" class="btn btn-outline">
        <i class="fa-solid fa-user-plus"></i> Register
    </a>
</div>
</div>

<!-- STATS -->
<div class="section">
    <div class="container">
        <div class="stats">
            <div class="stat-box"><h3>500+</h3><p>Tickets</p></div>
            <div class="stat-box"><h3>100+</h3><p>Users</p></div>
            <div class="stat-box"><h3>24/7</h3><p>Support</p></div>
            <div class="stat-box"><h3>99%</h3><p>Success</p></div>
        </div>
    </div>
</div>

<!-- FEATURES -->
<div class="section section-light">
    <div class="container">

        <div class="section-title">
            <h2>Key Features</h2>
            <p>Everything you need to manage support tickets</p>
        </div>

        <div class="features-grid">
            <div class="card feature-card">
                <i class="fa-solid fa-ticket"></i>
                <h3>Ticket Management</h3>
                <p>Create and track tickets easily</p>
            </div>

            <div class="card feature-card">
                <i class="fa-solid fa-users"></i>
                <h3>Role-Based Access</h3>
                <p>Admin, Agent, End User roles</p>
            </div>

            <div class="card feature-card">
                <i class="fa-solid fa-chart-line"></i>
                <h3>Dashboard</h3>
                <p>Real-time insights</p>
            </div>

            <div class="card feature-card">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Security</h3>
                <p>Secure authentication system</p>
            </div>
        </div>

    </div>
</div>

<!-- About Section -->

<div class="section">
    <div class="container">

        <div class="section-title">
            <h2>About This Project</h2>
            <p>Understanding the purpose of this system</p>
        </div>

        <div class="card" style="display:flex; gap:30px; align-items:center; flex-wrap:wrap;">

            <div style="flex:1; min-width:250px;">
                <h3>Smart Help Desk System</h3>
                <p style="color:#64748b; line-height:1.6;">
                    This project is designed to simplify support ticket management by providing 
                    a structured platform for users, agents, and administrators.
                    It improves communication, reduces response time, and ensures efficient issue tracking.
                </p>
            </div>

            <div style="flex:1; min-width:250px; text-align:center;">
                <i class="fa-solid fa-headset" style="font-size:60px; color:#3b82f6;"></i>
            </div>

        </div>

    </div>
</div>

<!-- HOW IT WORKS -->
<div class="section">
    <div class="container">

        <div class="section-title">
            <h2>How It Works</h2>
            <p>Simple 3-step process</p>
        </div>

        <div class="steps">
            <div>
                <div class="step-number">1</div>
                <p>Create Ticket</p>
            </div>
            <div>
                <div class="step-number">2</div>
                <p>Agent Resolves</p>
            </div>
            <div>
                <div class="step-number">3</div>
                <p>Track Progress</p>
            </div>
        </div>

    </div>
</div>

<!-- ROLES -->
<div class="features-grid">

    <div class="card feature-card">
        <i class="fa-solid fa-user-shield"></i>
        <h3>Admin</h3>
        <p>Manages users, tickets, and system settings</p>
    </div>

    <div class="card feature-card">
        <i class="fa-solid fa-headset"></i>
        <h3>Agent</h3>
        <p>Handles and resolves assigned tickets</p>
    </div>

    <div class="card feature-card">
        <i class="fa-solid fa-user"></i>
        <h3>User</h3>
        <p>Creates and tracks support tickets</p>
    </div>

</div>

<!-- CTA -->
<div class="section">
    <div class="container">

        <div class="card cta-card">

            <h2>Ready to get started?</h2>
            <p>Join now and manage your support tickets efficiently.</p>

            <div class="cta-buttons">
                <a href="register.php" class="btn">
                    <i class="fa-solid fa-user-plus"></i> Create Account
                </a>
            </div>

        </div>

    </div>
</div>

<!-- FOOTER -->
<div class="home-footer">
    © <?= date("Y"); ?> HelpDesk System
</div>

<div class="help-btn" onclick="openPanel()">?</div>
  
</div>

<div class="help-overlay" id="overlay" onclick="closePanel()"></div>
<!-- About Developer -->

<div class="help-panel" id="helpPanel">

    <div class="help-header">
        <h2>About Developer</h2>
        <span onclick="closePanel()">×</span>
    </div>

    <div class="help-content">

        <!-- PROFILE -->
        <div class="profile-box">
            <div class="avatar">S</div>
            <h3>Sawan Kumar Beniya</h3>
            <p>BCA Student</p>
        </div>

        <!-- SKILLS -->
        <div class="info-section">
            <h4>Skills</h4>
            <div class="tags">
                <span>PHP</span>
                <span>MySQL</span>
                <span>HTML</span>
                <span>CSS</span>
            </div>
        </div>

        <!-- PROJECTS -->
        <div class="info-section">
            <h4>Projects</h4>
            <ul>
                <li>HelpDesk Management System</li>
                <li>Project Submission & Evaluation System</li>
                <li>Document Saver</li>
            </ul>
        </div>

        <!-- CONTACT -->
        <div class="info-section">
            <h4>Contact</h4>
            <p><i class="fa-solid fa-envelope"></i> beniyasawan@email.com</p>
            <p><i class="fa-brands fa-linkedin"></i> https://www.linkedin.com/in/sawan-beniya-526115377</p>
        </div>

    </div>

</div>

<script>
function openPanel() {
    document.getElementById("helpPanel").classList.add("active");
    document.getElementById("overlay").classList.add("active");
}

function closePanel() {
    document.getElementById("helpPanel").classList.remove("active");
    document.getElementById("overlay").classList.remove("active");
}
</script>
</body>
</html>