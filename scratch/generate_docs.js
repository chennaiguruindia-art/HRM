const fs = require('fs');
const path = require('path');
const PDFDocument = require('pdfkit');
const { Document, Packer, Paragraph, TextRun, HeadingLevel, Table, TableRow, TableCell, WidthType, BorderStyle, AlignmentType, ShadingType } = require('docx');

const publicDocsDir = path.join(__dirname, '..', 'public', 'docs');
const artifactDir = 'C:\\Users\\GURU\\.gemini\\antigravity\\brain\\681705c0-8a4c-4d69-b223-4f1a9ce11574';

if (!fs.existsSync(publicDocsDir)) {
  fs.mkdirSync(publicDocsDir, { recursive: true });
}

// ---------------------------------------------------------
// 1. GENERATE PDF DOCUMENT
// ---------------------------------------------------------
function generatePDF(outputPath) {
  const doc = new PDFDocument({
    size: 'A4',
    margins: { top: 50, bottom: 50, left: 50, right: 50 },
    bufferPages: true
  });

  const stream = fs.createWriteStream(outputPath);
  doc.pipe(stream);

  const primaryColor = '#1e3a8a';   // Deep Blue
  const secondaryColor = '#0284c7'; // Sky Blue
  const darkTextColor = '#1e293b';  // Slate Dark
  const lightBgColor = '#f8fafc';   // Light Gray/Blue

  // Cover / Header
  doc.rect(50, 45, 495, 95).fill('#1e3a8a');
  doc.fillColor('#ffffff').fontSize(20).font('Helvetica-Bold').text('HR & Attendance Management System', 70, 65);
  doc.fontSize(12).font('Helvetica').text('System Architecture, Modules, Tech Stack & Workflow Guide', 70, 95);
  doc.fontSize(9).text('Comprehensive Beginner-Friendly Technical & Functional Documentation', 70, 115);

  doc.moveDown(4);

  // Helper Functions
  function addHeading1(title) {
    doc.moveDown(1.2);
    const y = doc.y;
    doc.rect(50, y, 495, 24).fill('#e0f2fe');
    doc.fillColor(primaryColor).fontSize(13).font('Helvetica-Bold').text(title, 58, y + 6);
    doc.moveDown(0.8);
  }

  function addHeading2(title) {
    doc.moveDown(0.8);
    doc.fillColor(secondaryColor).fontSize(11).font('Helvetica-Bold').text(title);
    doc.moveDown(0.4);
  }

  function addParagraph(text) {
    doc.fillColor(darkTextColor).fontSize(9.5).font('Helvetica').text(text, { align: 'justify', lineGap: 3 });
    doc.moveDown(0.4);
  }

  function addBullet(title, desc) {
    doc.fillColor(darkTextColor).fontSize(9.5).font('Helvetica-Bold').text('• ' + title + ': ', { continued: true });
    doc.font('Helvetica').text(desc, { lineGap: 2 });
    doc.moveDown(0.25);
  }

  // Section 1: Executive Overview
  addHeading1('1. Executive Overview');
  addParagraph('The HR & Attendance Management System (HRM) is an enterprise-ready web platform designed to streamline daily employee operations. It eliminates manual paper-based attendance tracking and simplifies HR operations including GPS-verified check-ins, auto-approved leave/permission quotas, automated milestone celebrations, and dynamic payroll calculations.');

  // Section 2: Tech Stack & Languages
  addHeading1('2. Technology Stack & Languages Used');
  addBullet('Backend Language & Framework', 'PHP 8.2+ powered by Laravel 11/12 (MVC Architecture, Eloquent ORM, REST APIs, Artisan CLI, Migrations, and Seeders).');
  addBullet('Frontend & Templating', 'Laravel Blade Engine, HTML5, CSS3, Tailwind CSS, Bootstrap 5 UI & Bootstrap Icons.');
  addBullet('Client-Side Scripting', 'JavaScript (ES6+), jQuery, AJAX for seamless real-time asynchronous updates without page reloads.');
  addBullet('Database Engine', 'MySQL / MariaDB (Production) & SQLite (Testing / Development).');
  addBullet('External APIs & Libraries', 'OpenStreetMap Nominatim API (Free reverse GPS geocoding), PhpSpreadsheet (Excel data imports/exports), Carbon (Time/Date arithmetic), DomPDF & PDFKit.');

  // Section 3: Core Modules Breakdown
  addHeading1('3. Core Functional Modules');

  addHeading2('Module A: Smart Attendance & Geolocation Check-In / Out');
  addBullet('One-Click Check-In/Out', 'Employees clock in and out directly from desktop or mobile devices.');
  addBullet('GPS & Address Resolver', 'Captures latitude & longitude coordinates and resolves them into human-readable street addresses.');
  addBullet('Automatic Status Logic', '>=8 hours = Present (Full Day), 5 to 8 hours = Half Day, <5 hours = Absent.');
  addBullet('Auto Clock-Out at 18:30', 'Automatically closes forgotten check-ins at 6:30 PM with precise working hour status.');
  addBullet('Daily Work Reports', 'Employees submit a concise daily log of tasks accomplished upon clocking out.');

  addHeading2('Module B: Leave & Permission Management Policy');
  addBullet('Request Category Selector', 'Interactive UI toggle between Standard Leave and 1-Hour Permission.');
  addBullet('1-Hour Permission Auto-Approval', 'Requests up to 2 permissions (2 hours total) per calendar month are auto-approved instantly.');
  addBullet('Automatic Quota Disabling', 'Once 2 permissions (2 hours) are used in a month, the permission button is automatically disabled ("Disabled - 2/2 Used"), preventing excess permissions and prompting the employee to apply for leave.');
  addBullet('Leave Balance Tracking', 'Real-time counters for Allocated Leaves, Used Leaves, Remaining Balance, and Pending Requests.');

  doc.addPage();

  addHeading2('Module C: Automated Employee Celebrations & Notifications');
  addBullet('Work Anniversary Engine', 'Automatically generates congratulatory notifications on the anniversary of an employee\'s join date (>= 1 year completed).');
  addBullet('Birthday Greetings', 'Delivers warm birthday wishes in the notification drawer on each employee\'s date of birth.');
  addBullet('Festive Dashboard Banners', 'Vibrant celebratory banner displayed at the top of the employee dashboard on their special day.');
  addBullet('Duplicate Prevention', 'Strict safeguards guarantee celebration messages fire exactly once per employee per calendar year.');
  addBullet('Admin Alerts', 'Real-time alerts inform HR administrators of milestone anniversaries, birthdays, and leave applications.');

  addHeading2('Module D: Dynamic Salary & Payroll Calculator');
  addBullet('Payroll Formula', 'Net Salary = (Base Salary / 30) * Worked Days (where Worked Days = Present + 0.5*HalfDay + Approved Paid Leaves).');
  addBullet('Join-Date Proration', 'Automatically calculates eligible days for new joiners starting mid-month.');
  addBullet('Records & Preview', 'Provides monthly salary previews and historical calculation records.');

  addHeading2('Module E: Multi-Branch & Administrative Control');
  addBullet('Branch Scoping', 'Branch Managers only view and manage employees assigned to their branch, while Super Admins manage all branches.');
  addBullet('Employee Directory', 'Complete employee records including photo profile, designation, emergency contacts, and blood groups.');
  addBullet('Holiday Calendar', 'Company-wide and branch-specific holiday configuration.');

  // Section 4: Workflow & Process Lifecycle
  addHeading1('4. System Workflow & Lifecycle');

  addHeading2('A. Employee Daily Flow');
  addParagraph('1. Employee Login: Navigates to /employee/login and enters their unique Employee ID.');
  addParagraph('2. Check-In: Hits Clock In; browser obtains GPS coordinates, resolves location address, and marks status.');
  addParagraph('3. Apply Leave / Permission: Selects Leave (Sick, Casual, Earned, Half Day) or Permission (1 hr auto-approved, max 2/month).');
  addParagraph('4. Check-Out: Submits daily work summary; system calculates hours and finalizes attendance status.');

  addHeading2('B. Admin Daily Flow');
  addParagraph('1. Dashboard Monitoring: Real-time statistics on total staff, present today, on leave today, and pending requests.');
  addParagraph('2. Approvals: Reviews and acts on pending leaves with automated attendance updating.');
  addParagraph('3. Payroll Generation: Runs salary preview and calculation at month end.');

  addHeading2('C. Background Automation Flow');
  addParagraph('1. 18:30:00 Daily: Unclosed attendances are automatically closed with calculated status.');
  addParagraph('2. Daily Check: Scans join_date and dob across active employees to send anniversary and birthday notifications.');
  addParagraph('3. 23:59:00 Daily: Non-attending active employees are automatically marked absent.');

  // Section 5: Step-by-Step Quick Start
  addHeading1('5. Quick Start & Setup');
  addParagraph('1. Install dependencies: composer install && npm install && npm run build');
  addParagraph('2. Setup Environment: cp .env.example .env && php artisan key:generate');
  addParagraph('3. Run Database: php artisan migrate --seed');
  addParagraph('4. Start Server: php artisan serve (Access at http://127.0.0.1:8000)');
  addParagraph('5. Run Tests: php artisan test');

  doc.end();
}

// ---------------------------------------------------------
// 2. GENERATE DOCX DOCUMENT
// ---------------------------------------------------------
async function generateDOCX(outputPath) {
  const doc = new Document({
    sections: [{
      properties: {},
      children: [
        new Paragraph({
          text: "HR & Employee Attendance Management System (HRM)",
          heading: HeadingLevel.TITLE,
          alignment: AlignmentType.CENTER,
        }),
        new Paragraph({
          text: "System Architecture, Modules, Tech Stack & Workflow Documentation",
          heading: HeadingLevel.HEADING_2,
          alignment: AlignmentType.CENTER,
        }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "1. Executive Overview", heading: HeadingLevel.HEADING_1 }),
        new Paragraph({
          text: "The HR & Attendance Management System (HRM) is an enterprise-ready web platform designed to streamline daily employee operations. It eliminates manual paper-based attendance tracking and simplifies HR operations including GPS-verified check-ins, auto-approved leave/permission quotas, automated milestone celebrations, and dynamic payroll calculations."
        }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "2. Technology Stack & Languages Used", heading: HeadingLevel.HEADING_1 }),
        new Paragraph({
          children: [
            new TextRun({ text: "• Backend: ", bold: true }),
            new TextRun("PHP 8.2+ with Laravel 11/12 (MVC architecture, Eloquent ORM, REST APIs, Artisan, Migrations & Seeders).")
          ]
        }),
        new Paragraph({
          children: [
            new TextRun({ text: "• Frontend: ", bold: true }),
            new TextRun("Blade Templating Engine, HTML5, CSS3, Tailwind CSS, Bootstrap 5 UI & Icons.")
          ]
        }),
        new Paragraph({
          children: [
            new TextRun({ text: "• Scripting: ", bold: true }),
            new TextRun("JavaScript (ES6+), jQuery, AJAX for asynchronous real-time updates.")
          ]
        }),
        new Paragraph({
          children: [
            new TextRun({ text: "• Database: ", bold: true }),
            new TextRun("MySQL / MariaDB / SQLite.")
          ]
        }),
        new Paragraph({
          children: [
            new TextRun({ text: "• External Services: ", bold: true }),
            new TextRun("OpenStreetMap Nominatim Geocoding API, PhpSpreadsheet, Carbon, DomPDF.")
          ]
        }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "3. Core Functional Modules", heading: HeadingLevel.HEADING_1 }),

        new Paragraph({ text: "Module A: Smart Attendance & Geolocation Check-In / Out", heading: HeadingLevel.HEADING_2 }),
        new Paragraph({ text: "• Clock In / Out with GPS coordinates and automatic reverse geocoding to physical street addresses." }),
        new Paragraph({ text: "• Automatic Status Calculation: >=8h Present, 5-8h Half Day, <5h Absent." }),
        new Paragraph({ text: "• Auto Clock-Out at 6:30 PM (18:30) for unclosed sessions." }),
        new Paragraph({ text: "• Daily Work Report submission on clock-out." }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "Module B: Leave & Permission Management Policy", heading: HeadingLevel.HEADING_2 }),
        new Paragraph({ text: "• Request Category Toggle: Choose cleanly between Leave and 1-Hour Permission." }),
        new Paragraph({ text: "• 1-Hour Permission Auto-Approval: Max 1h/day, up to 2 permissions (2h) per month auto-approved." }),
        new Paragraph({ text: "• Quota Disabling: Once 2 permissions are used in a month, the permission button is automatically disabled (Disabled - 2/2 Used), prompting the employee to apply for Leave." }),
        new Paragraph({ text: "• Leave Balances: Real-time tracking of Allocated, Used, Remaining, and Pending leaves." }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "Module C: Automated Celebrations & Notifications", heading: HeadingLevel.HEADING_2 }),
        new Paragraph({ text: "• Work Anniversary Celebrations: Congratulatory messages on join_date anniversary (>=1 year)." }),
        new Paragraph({ text: "• Birthday Wishes: Celebratory notifications delivered on employee date of birth (dob)." }),
        new Paragraph({ text: "• Dashboard Banners: Celebratory top banners on employee special days." }),
        new Paragraph({ text: "• Duplicate Prevention: Guarantees celebration messages fire only once per year." }),
        new Paragraph({ text: "• Admin Alerts: Real-time notification drawer for admin oversight." }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "Module D: Dynamic Salary & Payroll Calculations", heading: HeadingLevel.HEADING_2 }),
        new Paragraph({ text: "• Formula: Final Salary = (Base Salary / 30) * Worked Days." }),
        new Paragraph({ text: "• Worked Days = Present Days + (Half Days * 0.5) + Approved Paid Leaves." }),
        new Paragraph({ text: "• Prorated eligible days based on employee joining date." }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "Module E: Administration & Multi-Branch Management", heading: HeadingLevel.HEADING_2 }),
        new Paragraph({ text: "• Branch Scoping: Role-based permissions for Branch Managers and Super Admins." }),
        new Paragraph({ text: "• Employee Directory: Profiles, photos, designations, emergency contacts." }),
        new Paragraph({ text: "• Holiday Calendar & Old Data Excel Spreadsheet Import." }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "4. System Workflow & Process Lifecycle", heading: HeadingLevel.HEADING_1 }),
        new Paragraph({ text: "• Employee Journey: Login -> Geolocation Check-In -> Daily Work -> Check-Out with Report -> Apply Leave/Permission." }),
        new Paragraph({ text: "• Admin Journey: Dashboard Monitoring -> Employee & Branch Administration -> Attendance Auditing -> Leave Decisioning -> Payroll Generation." }),
        new Paragraph({ text: "• Background Automation: 18:30 Auto Clock-Out -> Daily Anniversary & Birthday generation -> 23:59 Mark Absent." }),
        new Paragraph({ text: "" }),

        new Paragraph({ text: "5. Installation & Quick Start", heading: HeadingLevel.HEADING_1 }),
        new Paragraph({ text: "1. composer install && npm install && npm run build" }),
        new Paragraph({ text: "2. cp .env.example .env && php artisan key:generate" }),
        new Paragraph({ text: "3. php artisan migrate --seed" }),
        new Paragraph({ text: "4. php artisan serve (http://127.0.0.1:8000)" }),
        new Paragraph({ text: "5. php artisan test" }),
      ]
    }]
  });

  const buffer = await Packer.toBuffer(doc);
  fs.writeFileSync(outputPath, buffer);
}

async function main() {
  const pdfPublic = path.join(publicDocsDir, 'HRM_System_Documentation.pdf');
  const docxPublic = path.join(publicDocsDir, 'HRM_System_Documentation.docx');
  const pdfArtifact = path.join(artifactDir, 'HRM_System_Documentation.pdf');
  const docxArtifact = path.join(artifactDir, 'HRM_System_Documentation.docx');

  console.log('Generating PDF...');
  generatePDF(pdfPublic);
  generatePDF(pdfArtifact);

  console.log('Generating DOCX...');
  await generateDOCX(docxPublic);
  await generateDOCX(docxArtifact);

  console.log('Documentation files successfully generated:');
  console.log('- PDF (Public): ' + pdfPublic);
  console.log('- DOCX (Public): ' + docxPublic);
  console.log('- PDF (Artifact): ' + pdfArtifact);
  console.log('- DOCX (Artifact): ' + docxArtifact);
}

main().catch(err => console.error(err));
