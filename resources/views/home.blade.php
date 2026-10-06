<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WeTalk — Community Communication for Everyone</title>
    <meta name="description" content="Secure community messaging over Wi-Fi or any internet connection. No SIM required.">
    <link rel="canonical" href="https://www.wetalk.co/">
    <link rel="stylesheet" href="{{ asset('css/wetalk.css') }}">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="nav">
    <a class="logo brand-logo" href="#home" aria-label="WeTalk home"><span class="official-mark"></span><span>We<span>talk</span></span></a>
    <nav aria-label="Main navigation"><a class="active" href="#home">Home</a><a href="#features">Features</a><a href="#communities">Communities</a><a href="#security">Security</a><a href="#pricing">Pricing</a><a href="#about">About Us</a></nav>
    <div class="nav-actions"><a href="{{ route('dashboard') }}">Log in</a><a class="button red" href="{{ route('dashboard') }}">Open Dashboard</a></div>
    <details class="mobile-menu"><summary aria-label="Open navigation">☰</summary><div><a href="#features">Features</a><a href="#communities">Communities</a><a href="#security">Security</a><a href="#pricing">Pricing</a><a href="#about">About</a></div></details>
</header>

<main id="main-content">
<section class="hero" id="home">
    <div class="hero-copy">
        <div class="tag">● &nbsp; CONECTING PEOPLE WORLD WIDE</div>
        <h1>The Community Communication <span class="blue">Platform</span> for <span>Everyone</span></h1>
        <p>WeTalk makes it easy for communities, teams, schools, and organizations to connect and communicate—anytime, anywhere. No physical SIM. No eSIM. No limits.</p>
        <div class="buttons"><a class="button" href="{{ route('dashboard') }}">Create Your Community →</a><a class="button outline" href="{{ route('dashboard') }}">Open Dashboard</a></div>
        <div class="proof"><span class="blue-proof">◉ <b>Works Anywhere</b><small>Wi-Fi or Local Network</small></span><span class="red-proof">▣ <b>Secure &amp; Private</b><small>End-to-End Protection</small></span><span class="purple-proof">◉ <b>Built for Communities</b><small>Channels, Roles &amp; Events</small></span></div>
    </div>
    <div class="app-stage">
        <div class="color-orb"></div>
        <div class="app-card">
            <aside><div class="mini-logo">◖◗ We<span>talk</span></div><a class="selected">⌂ Home</a><a>♙ Communities</a><a># Channels</a><a>☵ Direct Messages</a><a>♧ Notifications</a><a>▣ Events</a><a>▤ Files</a><a>☆ Saved Messages</a><a>⚙ Settings</a></aside>
            <div class="chat"><header><h3># &nbsp; general</h3><small>General discussion for everyone.</small></header>
                <article><i class="avatar blue-bg">MI</i><p><b>Musa Ibrahim</b><small>10:30 AM</small><span>Good morning everyone! 👋<br>Hope you all have a great day.</span></p></article>
                <article><i class="avatar red-bg">AB</i><p><b class="red-text">Aisha Bello</b><small>10:32 AM</small><span>Good morning Musa! ☀️<br>Yes, have a productive day everyone.</span></p></article>
                <article><i class="avatar purple-bg">IM</i><p><b>Ibrahim Musa</b><small>10:35 AM</small><span>Don’t forget our community meeting tomorrow at 6 PM.</span></p></article>
                <div class="composer">📎 &nbsp; Type a message… <b>➤</b></div>
            </div>
            <div class="members"><b>Online Members (23)</b><span>🟢 Musa Ibrahim</span><span>🟢 Aisha Bello</span><span>🟢 Ibrahim Musa</span><span>🟢 Fatima Ali</span><span>🟢 Usman K.</span><small>+17 more</small></div>
        </div>
    </div>
</section>

<section class="trusted"><h3>Trusted by communities, teams, and organizations worldwide</h3><div><span>🏫 Schools</span><span>🎓 Universities</span><span>💼 Businesses</span><span>✝ Churches</span><span>♡ NGOs</span><span>▣ Events</span></div></section>

<section class="features section" id="features"><div class="section-head"><small>EVERYTHING IN ONE PLACE</small><h2>Powerful features that bring <span>people together</span></h2><p>Everything your community needs to stay connected and move forward.</p></div><div class="feature-grid">
@foreach ([['#','Channels','Keep teams and topics organized.'],['!','Announcements','Share updates everyone can see.'],['31','Events','Plan meetups and send reminders.'],['?','Polls','Turn questions into decisions.'],['+','File Sharing','Share images and documents securely.'],['♫','Voice Notes','Say more when typing is not enough.'],['文','Language Translation','Translate messages in real time for multilingual communities.'],['💬','Personal Chat','Have secure one-to-one conversations with other members.'],['▦','QR Join','Invite anyone with one quick scan.'],['✦','AI Assistant','Catch up with summaries and replies.']] as [$icon,$title,$copy])
<article><i>{{ $icon }}</i><h3>{{ $title }}</h3><p>{{ $copy }}</p></article>
@endforeach
</div></section>

<section class="community section" id="communities"><div><small>BUILT FOR REAL PEOPLE</small><h2>One app. <span>Every conversation.</span></h2><p>Create welcoming spaces for classrooms, neighborhoods, clubs, projects, and families.</p><ul><li>✓ Join instantly with QR invitations</li><li>✓ Organize channels, roles, and events</li><li>✓ Communicate without sharing a phone number</li></ul></div><div class="qr-card"><div class="qr"></div><h3>Join your community</h3><p>Scan to connect</p></div></section>

<section class="security section" id="security"><div><small>PRIVATE BY DESIGN</small><h2>Your conversations belong to you.</h2><p>End-to-end encryption keeps personal chats personal. Your username—not your phone number—is your identity.</p></div><div class="security-list"><p><b>No SIM or eSIM</b><span>Connect using the internet.</span></p><p><b>No phone number required</b><span>Keep your personal number private.</span></p><p><b>End-to-end encrypted</b><span>Only participants can read private chats.</span></p></div></section>

<section class="pricing section" id="pricing"><div class="section-head"><small>SIMPLE PRICING</small><h2>Start free. Grow <span>together.</span></h2><p>Clear plans for every kind of community, with no SIM or phone number required.</p></div><div class="pricing-grid"><article><h3>Personal</h3><strong>Free</strong><p>For families, friends, and small groups.</p><ul><li>✓ Private messaging</li><li>✓ Voice notes and files</li><li>✓ QR invitations</li></ul><a class="button outline" href="#join">Get Started</a></article><article class="popular"><div class="popular-label">MOST POPULAR</div><h3>Community</h3><strong>$6<small>/month</small></strong><p>For schools, clubs, churches, and teams.</p><ul><li>✓ Unlimited channels</li><li>✓ Roles and permissions</li><li>✓ Events, polls, and announcements</li></ul><a class="button" href="#join">Start Your Community</a></article><article><h3>Organization</h3><strong>Custom</strong><p>For larger networks and institutions.</p><ul><li>✓ Advanced administration</li><li>✓ Priority assistance</li><li>✓ Tailored onboarding</li></ul><a class="button outline" href="mailto:hello@wetalk.co">Contact Us</a></article></div></section>

<section class="faq section"><div class="section-head"><small>FREQUENTLY ASKED</small><h2>Questions, answered.</h2></div><div class="faq-list"><details open><summary>Does WeTalk require a SIM card?</summary><p>No. WeTalk works through Wi-Fi, a local network, or any available internet connection.</p></details><details><summary>Do I need to share my phone number?</summary><p>No. WeTalk is designed around usernames and secure community invitations.</p></details><details><summary>Are private messages protected?</summary><p>Private chats use end-to-end encryption so only the participants can read them.</p></details><details><summary>Can I create a community for my school or organization?</summary><p>Yes. Channels, roles, announcements, events, polls, and QR invitations are built for organized communities.</p></details></div></section>

<section class="join section" id="join"><small>START TALKING TODAY</small><h2>Your community is one conversation away.</h2><p>Create your WeTalk space—no SIM, no borders, no limits.</p><a class="button red" href="mailto:hello@wetalk.co">Join the Waitlist →</a></section>
</main>
<footer id="about"><div class="logo brand-logo footer-logo"><span class="official-mark"></span><span>We<span>talk</span></span></div><p>CONECTING PEOPLE WORLD WIDE</p><nav aria-label="Footer navigation"><a href="#features">Features</a><a href="#pricing">Pricing</a><a href="#security">Privacy</a><a href="mailto:hello@wetalk.co">Contact</a><a href="https://www.wetalk.co/">www.wetalk.co</a></nav><small>© {{ date('Y') }} WeTalk. All rights reserved.</small><p class="developer-credit">Developed by FEEZ Tech Limited</p></footer>
</body>
</html>
