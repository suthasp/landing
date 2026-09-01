<?php
declare(strict_types=1);

return [
    'html_lang' => 'en',
    'meta' => [
        'title'       => 'TEXSON — Data Center Facility Consultant | Audit · PM Planning · Training',
        'description' => 'Server room and Data Center facility consulting: facility audits, preventive maintenance planning, and in-house technician training from real hands-on operations experience.',
    ],

    'nav' => [
        'services' => 'Services',
        'products' => 'Products',
        'why'      => 'Why Us',
        'process'  => 'Process',
        'contact'  => 'Contact',
        'quote'    => 'Request Quote',
        'menu'     => 'Menu',
        'theme'    => 'Toggle light / dark mode',
        'lang'     => 'เปลี่ยนเป็นภาษาไทย',
        'webmail'  => 'Webmail Login',
    ],

    'hero' => [
        'badge'      => 'Data Center Facility & Critical Environment Consultant',
        'title_1'    => 'Is Your Server Room Ready',
        'title_2'    => 'for Power Outages & System',
        'title_mark' => ' Failures?',
        'lead'       => 'We audit, plan preventive maintenance, and train your team — with real hands-on experience in Facility Operation, Preventive & Corrective Maintenance inside professional-grade Data Centers — so your systems stay up, not just recover quickly.',
        'cta_1'      => 'Free Consultation',
        'cta_2'      => 'Our Services',
    ],

    'stats' => [
        ['value' => '10+ Yrs', 'label' => 'Real Facility Operation Experience'],
        ['value' => '24/7',    'label' => 'Insights from Real Operations, Not Just Theory'],
        ['value' => '100%',    'label' => 'Reports with Actionable Fix Plans'],
    ],

    'problems' => [
        'title'    => 'Do Any of These Sound Familiar?',
        'subtitle' => 'Most organizations have a server room — but no one managing Facility systems properly.',
        'items' => [
            [
                'icon'  => '⚡',
                'title' => 'UPS Has Never Been Tested',
                'text'  => 'Batteries degrade silently. You only find out when the power fails and the whole company goes down.',
            ],
            [
                'icon'  => '🌡️',
                'title' => 'AC Fails at Night, No Backup Plan',
                'text'  => 'Temperature spikes, equipment shuts down, no SOP for who does what.',
            ],
            [
                'icon'  => '📋',
                'title' => 'No Systematic PM Plan',
                'text'  => 'Reactive-only maintenance (Corrective). Hidden costs far exceed preventive investment.',
            ],
            [
                'icon'  => '👷',
                'title' => 'Technicians Lack Specialized Knowledge',
                'text'  => "They handle general buildings but don't understand Critical Environments for server rooms.",
            ],
        ],
    ],

    'services' => [
        'title'    => 'Our Services',
        'subtitle' => 'From facility assessment to building a self-sufficient team — we cover it all.',
        'best_for' => 'Best for:',
        'items' => [
            [
                'no'     => 'SERVICE 01',
                'title'  => 'Server Room / Data Center Facility Audit',
                'points' => [
                    'Inspect electrical systems, UPS, and generators',
                    'Evaluate precision air conditioning / cooling systems',
                    'Fire suppression, access control, and monitoring systems',
                    'Assess Single Point of Failure risks',
                    'Report + prioritized action plan by urgency',
                ],
                'note'   => 'Organizations with their own server room who want to identify risks before an incident.',
            ],
            [
                'no'     => 'SERVICE 02',
                'title'  => 'Preventive Maintenance (PM) Planning',
                'points' => [
                    'Design weekly / monthly / annual PM plans for all systems',
                    'Create Data Center standard checklists and SOPs',
                    'Define corrective maintenance and escalation procedures',
                    'Identify critical spare parts to keep on hand',
                    'Ready-to-use documentation delivered to your team',
                ],
                'note'   => 'Organizations that repair on failure and want to shift to proactive, systematic protection.',
            ],
            [
                'no'     => 'SERVICE 03',
                'title'  => 'In-house Technician / Engineer Training',
                'points' => [
                    'Critical Environment fundamentals for server rooms',
                    'PM procedures for electrical systems, UPS, and HVAC',
                    'Emergency response procedures',
                    'Real case studies — not just slides',
                    'On-site training at your facility',
                ],
                'note'   => 'Organizations that want their team to manage systems independently, reducing reliance on external vendors.',
            ],
        ],
    ],

    'products' => [
        'title'    => 'Products & Equipment',
        'subtitle' => 'We source facility equipment for server rooms and specify it to match your real site conditions — vendor-neutral, not tied to any single brand.',
        'items' => [
            [
                'icon'  => '⚡',
                'title' => 'Power & Backup Systems',
                'list'  => ['UPS units and backup batteries', 'MDB and distribution panels', 'ATS and generators'],
            ],
            [
                'icon'  => '❄️',
                'title' => 'Server Room Cooling',
                'list'  => ['Precision air / in-row cooling', 'Hot / cold aisle containment', 'Spare parts and maintenance kits'],
            ],
            [
                'icon'  => '🗄️',
                'title' => 'Racks & Infrastructure',
                'list'  => ['Server racks and cable management', 'PDUs and in-rack power distribution', 'Raised floor and structured cabling'],
            ],
            [
                'icon'  => '📡',
                'title' => 'Monitoring & Safety',
                'list'  => ['Temperature, humidity, and water-leak monitoring', 'Fire suppression for server rooms', 'Access control and CCTV'],
            ],
        ],
    ],

    'why' => [
        'title'    => 'Why Choose Us',
        'subtitle' => "We're not consultants who only know theory — we're the people who run systems every day.",
        'items' => [
            [
                'title' => 'Real On-site Experience',
                'text'  => "We've performed Operation and Maintenance in real Data Centers and seen every failure scenario firsthand — not just in textbooks.",
            ],
            [
                'title' => 'Actionable Reports',
                'text'  => 'Not a 100-page report nobody reads. A prioritized action plan with clear budget breakdowns.',
            ],
            [
                'title' => 'Vendor-Neutral Advice',
                'text'  => "We don't represent any equipment brand. Our recommendations are driven entirely by your best interest.",
            ],
            [
                'title' => "We Build Your Team's Capability",
                'text'  => 'Our goal is for your team to manage systems confidently on their own — not to keep you dependent on us.',
            ],
        ],
    ],

    'process' => [
        'title'    => 'How We Work',
        'subtitle' => 'Simple start. Clear pricing before you commit.',
        'items' => [
            ['title' => 'Initial Consultation (Free)', 'text' => 'Discuss your issues and needs by phone or online — about 30 minutes.'],
            ['title' => 'Site Survey',                 'text' => 'Visit your site, assess the scope of work, and provide a quote.'],
            ['title' => 'Execution',                   'text' => 'Audit / PM planning / Training — as agreed in scope.'],
            ['title' => 'Delivery + Follow-up',        'text' => 'Complete reports and documentation, plus ongoing advisory support.'],
        ],
    ],

    'contact' => [
        'title'       => 'Free Consultation',
        'subtitle'    => "Tell us your problem or requirements. We'll get back to you within 1 business day.",
        'phone_label' => 'Phone',
        'email_label' => 'Email',
        'hours_label' => 'Business Hours',
        'hours'       => 'Mon–Sat 9:00–18:00',
        'form' => [
            'name'       => 'Full Name',
            'name_ph'    => 'Your name',
            'company'    => 'Company / Organization',
            'company_ph' => 'Company name',
            'contact'    => 'Phone / Email',
            'contact_ph' => 'How we can reach you',
            'service'    => 'Service of Interest',
            'details'    => 'Additional Details',
            'details_ph' => 'Briefly describe your problem or needs',
            'submit'     => 'Send Message',
            'sending'    => 'Sending...',
            'service_options' => [
                'audit'    => 'Server Room / Data Center Audit',
                'pm'       => 'Preventive Maintenance (PM) Planning',
                'training' => 'In-house Technician / Engineer Training',
                'product'  => 'Products & Equipment',
                'other'    => 'Other / Not sure yet',
            ],
        ],
        'messages' => [
            'success'      => "Your message has been sent. Thank you — we'll get back to you within 1 business day.",
            'error'        => 'Please check the information you entered.',
            'name_req'     => 'Please enter your full name.',
            'contact_req'  => 'Please enter a phone number or email so we can reach you.',
            'contact_bad'  => 'That phone number or email address looks invalid.',
            'details_long' => 'Details are too long (2,000 characters max).',
            'csrf'         => 'Your session expired. Please submit the form again.',
            'throttle'     => 'You just sent a message. Please wait a moment before sending another.',
        ],
    ],

    'footer' => [
        'copyright'     => '© %s Texson — Data Center Facility Consultant · Audit · PM Planning · Training',
        'tagline'       => 'Server room and Critical Environment consulting — from facility audit and PM planning to training your own team.',
        'nav_title'     => 'Menu',
        'contact_title' => 'Contact',
        'back_to_top'   => 'Back to top',
    ],
];
