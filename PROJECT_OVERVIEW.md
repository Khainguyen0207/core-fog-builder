# **System Overview Document: Motorbike Service Manager**

## **1. System Overview**

**Motorbike Service Manager** is a comprehensive software solution specifically designed for managing and operating motorbike and car maintenance, repair, and detailing centers.

**Suitable for business models:**

- Maintenance chain stores, repair garages.
- Professional car wash and vehicle detailing centers.
- Service business models requiring advance appointment booking, with a team of technicians that need to be allocated and have their work schedules tracked.

## **2. Core Features**

The system is divided into core modules, covering the entire digitalization needs of a service enterprise:

### **👤 End-user Features (Customers)**

The Moto Service Manager system is designed to bring absolute convenience, transparency, and peace of mind to customers when experiencing vehicle care and maintenance services:

- **1. Proactive & Quick Service Booking:** Customers no longer have to bring their vehicles to the store and wait in line for their turn. Every action can be done online in just a few minutes:
    - **Flexible service selection:** Easily view the list and select the exact repair and maintenance items needed.
    - **Time & Personnel options:** Proactively choose the day and time to bring the vehicle in. Instead of being assigned randomly, customers can point out a familiar or trusted technician to take care of their vehicle.
    - **Instant promotions:** Apply discount codes (coupons) to get offers right during the booking process.

- **2. Transparent Repair Progress Monitoring:** This feature brings immense peace of mind, completely solving the anxiety of "not knowing what the mechanic is doing to my vehicle":
    - **Lookup by code (Booking Code):** Manually enter the booking number to check the entire current status of the vehicle.
    - **Control each item:** The system displays detailed progress: which services are done, in progress, actual start and end times of each stage.
    - **Clear costs:** A summary table of costs for each individual service and the total payment amount, ensuring there are no hidden fees.

- **3. Personal Account Ecosystem:** A private space for customers to manage their vehicle's "health":
    - **Loyal customers:** Unlock membership tiers based on total accumulated spending. Enjoy ranking privileges set up by the store.
    - **Service History:** Store all previous repairs. Customers easily know when their last oil change or major maintenance was.
    - **Seamless login:** Flexible registration and support for ultra-fast login using Google Social network accounts, skipping the steps of remembering another complex password. Quickly recover passwords using an OTP code system.

- **4. Flexible & Safe Payment:**
    - Customers can choose face-to-face payment after completing the service at the store, or remote payment via high-security bank transfer (VietQR code).

- **5. Service Interaction & Feedback:**
    - **Review/Rating rights:** After each service experience, customers can directly rate and leave comments on the skill quality and attitude of the technician.
    - **Motorbike handbook (Blog & News):** Look up maintenance tips and basic troubleshooting guides right on the store's website.
    - **24/7 Support:** Always available direct contact icons to the Hotline or Zalo of the store for immediate answers to inquiries.

### **👨‍🔧 Staff Features (Technicians)**

A dedicated interface empowering technicians to proactively manage their tasks and performance:

- **Staff Dashboard & Authentication:** A secure, isolated login environment. The dashboard provides an instant overview of their performance: total number of allocated schedules (daily, weekly, monthly), their average customer rating, and a quick list of immediate upcoming tasks.
- **Schedule Management:** A comprehensive calendar view allowing staff to track their assigned repairing and maintenance bookings. They can flexibly filter schedules by today, this week, or this month to prepare for upcoming shifts.
- **Reviews & Feedback:** Staff can directly read detailed, paginated reviews left by customers specifically for the services they performed, aiding in continuous skill and attitude improvement.
- **Profile & Internal News:** A dedicated space for staff to update personal details, track total working time on services, and stay updated with internal announcements from the management.

### **💼 Admin Features**

Below is a detailed list of administrative modules built comprehensively, covering all operational workflows:

- **Dashboard:** Visually statistics on growth KPIs (revenue, visitors), checking recent activities, daily booking schedule charts, Top Services / Categories / Access Areas statistics, and payment method ratio.
- **Bookings & Calendar Management:** Manage the entire lifecycle of an appointment order. Flexibly handle order status, view technical reviews in detail. Support viewing as an intuitive **Calendar** chart and automatic PDF Invoice export feature.
- **Customers & Users Management:** Manage customer status and profiles. Manage administrative account (Users) permissions with strict self-protection mechanisms (e.g., prevent Admins from deleting the account they are currently logged in with).
- **Membership Settings:** Dynamically configure member tiers and point accumulation quotas to stimulate customers' service experience needs.
- **Services & Categories Management:** Systematize maintenance and car wash service packages, powerfully hierarchize service categories.
- **Staff & Reviews Management:** Manage technician profiles, assign specialized services to mechanics. Integrate flexible configurations to limit the amount of staff receiving schedules (Active Staff). Update and cross-check reviews separated from customers.
- **Payment & Transactions Management:** Monitor the status of all prepaid and over-the-counter payment transactions across all methods.
- **Coupons & Redemptions Management:** Build discount campaigns (cash discount, % discount). Strictly set up conditions for application (Coupon Applicables) and keep detailed tracks of successful redemption history (Redemptions).
- **Content Management (Blog / Posts / Tags):** Manage complete articles and news CMS, with a keyword system (Tags), categories (Blog Categories), cover images, and a comment box. Automatically generate standardized Slugs ensuring SEO.
- **System Settings:** Centralized configuration module to change parameters without recoding. Includes changing open/close working hours, overall contact info, point accumulation, automatic linking (SePay), and integration API code for the instant reminder system (Telegram Bot).
- **System Utilities (Log Viewer & Bulk Delete):** The ability to review software error history (Log viewer) directly on the admin page. Apply mass Bulk Delete mechanisms easily, optimizing administration speed.

### **💳 Payment & Marketing Features**

- **Payment:** Integrated with bank transfer payment flow via scan code (VietQR), optimizing the cashless experience. The system will automatically confirm the transaction once a successful notification from the bank is received.
- **Coupons:** Proactively create and issue discount codes for the system. Control expiration dates, applicable audiences, and costs to run Marketing campaigns effectively.

### **⚙️ System Features**

- **General Settings:** Allows admin to flexibly setup and customize open/close times, overall brand info, banking info, max number of staff working in a time frame, automatic linking (SePay), integration API code for instant reminder system (Telegram Bot).
- **System & Security:** Carefully store all system activity logs (System logs), transparently monitor traffic and visitor sources.

## **3. System Strengths**

The system does not stop at the interface but also contains a sustainable architecture inside the enterprise model:

- **Seamless All-in-One:** Has pre-integrated all essential operations of a tough Booking system such as: Detailed appointment management, Coupon management, Permission standardization. Project owners buying it don't have to spend a lot of money to rebuild discrete features.
- **Clear, Transparent System Architecture:** The interface optimizes for a fast and neat experience; meanwhile, the core system is built strictly and tightly, undertaking to bear the load of all complex data operations safely.
- **Premium Admin Dashboard ready:** Integrates a complete, modern operational interface exclusively for the business owner to use immediately with all data centralized on a central screen.
- **Easy to Expand:** The source code strictly adheres to international programming standards (clean code), helping technical teams taking over later to easily continue upgrading and customizing without breaking existing features.
- **Smart Booking Algorithm — Optimizing every idle minute:** This is the core difference compared to major booking systems on the market (e.g., 30Shine). Traditional systems usually divide time into fixed "slices" (e.g., every 20 mins = 1 slot). This approach leads to **serious waste**: if a real service only takes 25 minutes, the system will entirely occupy 2 slots (40 minutes), for the remaining 15 minutes the technician has to sit and wait without being able to receive a new customer. Moto Service Manager thoroughly solves this problem with the **Continuous Scheduling mechanism**:
    - The duration of each service is dynamically calculated based on the actual time (minutes) of each service, not constrained to a slot mold.
    - When customers book multiple services, the system automatically **chains** the services back-to-back, matching every minute without creating wasteful gaps.
    - Check for overlapping staff schedules accurately to the minute, ensuring technicians are always utilized to their maximum productivity and never double-booked.
    - Result: **Significantly increased customer reception productivity**, the store serves more customers in the same working day compared to the fixed slot model.

## **4. Advanced Features (High-end Product Line)**

Besides the standard core, the system pre-integrates advanced technologies designed for enterprise applications:

- **Instant Notifications via Telegram:** All appointment booking fluctuations (new appointments, rescheduling, etc.) are pushed straight to the store owner's or manager's phone via the **Telegram** app. Business owners don't need to sit at a computer but can still quickly capture and close schedules anytime, anywhere.
- **Email Marketing & Campaigns:** An integrated system module (`admin/email-marketing`) allowing administrators to:
    - **Templates Management:** Build, manage, and arrange pre-designed email templates natively without third-party template builders.
    - **Marketing Send Flow:** Push/schedule automated marketing material or promotional notifications directly to targeted customer groups.
- **Automatic Confirmation Emails:** Right after a successful booking, the system automatically sends a confirmation email with a professional interface to the customer's mailbox. The entire process happens silently in the background, without slowing down the customer's website usage experience.
- **Automated Operations, Reducing Supervision Personnel:** The system automatically handles repetitive operations without human intervention. Example: Auto cancel unpaid orders over 24 hours, auto cross-check transfer status periodically — helping store owners safely operate without tracking every transaction.
- **Automated QR Code Payment:** Pre-integrated with QR code bank transfer payment gateway (via partners like SePay). The system automatically recognizes when a customer successfully transfers money and updates the order status immediately — completely eliminating manual confirmation steps.
- **Safe Protection Against Cyber Attacks:** The system is equipped with a protection layer against spam and automated attacks (e.g., continuous OTP requests, massive booking), keeping the website always stable and safe for all users.

## **5. System Status**

- **Production-ready:** The project is professionally designed down to every detail. Ready to serve deployment for the production environment of real business models.
- **Immediate Deploy Time:** Can be configured and packaged to put online and operate immediately in a short time without requiring logic modifications.
- **Advanced Docker Architecture:** Both the user and admin modules have been equipped with a complete **Docker/Docker Compose**, bringing convenience when deploying on any server system and easily applying automated distribution flows (CI/CD).

## **6. Future Expansion Potential**

Thanks to a highly ready core shaping, the owning team entirely has the upper hand to develop further for the future:

- **Opportunity to launch Mobile App instantly:** The fact that the source code is pre-equipped with a separate API platform helps the ability to smash the Front-end version into a Mobile App ecosystem (iOS / Android) for buyers becoming standardized - without rebuilding the central processing block.
- **Diverse Payment Gateways:** The payment module structure is currently programmed flexibly, easy to cross-connect with new payment gateways (such as Momo, VNPay, ZaloPay, Stripe or PayPal) depending on the brand's target user approach goal.
- **Scale up to multi-branch (Multi-tenant):** The initial system flow platform clearly separates owner objects, creating a perfect stepping stone for transforming the system into a chain platform with multiple warranty facilities in multiple different geographical locations.