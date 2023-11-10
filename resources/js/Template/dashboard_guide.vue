<script>
import { Link } from "@inertiajs/inertia-vue3";
import { ref, defineEmits } from "vue";

export default {
    data: () => ({}),
    setup(props, context) {},
};
</script>

<template>
    <fieldset class="mt-2 border border-solid border-gray-300 p-3">
        <legend class="text-xl font-medium text-blue-500">
            Guides and Information:
        </legend>
        <div class="content-center gap-1 px-2 py-2">
            <p class="mb-2 text-2xl font-medium">
                Hafa Adai and Tirow! Welcome to your Reports page.
            </p>
            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <p class="text-base font-normal">
                    This is where you will submit your Quarterly Reports as an
                    Employer.
                </p>

                <p class="text-base font-normal">
                    According to § 80-20.1-505 Census of Employment, (b) Each
                    business employer shall report quarterly, as of the last day
                    of the calendar quarter and within the time limits for
                    filing the business gross receipts tax return, the number
                    and classification of employees for whom wages were paid
                    during the quarter.
                </p>
            </div>
            <p class="mb-2 text-lg font-medium">What is a Workforce Plan?</p>
            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <p class="text-base font-normal">
                    According to § 80-20.1-510 Workforce Plan, (b) A workforce
                    plan shall identify specific positions currently occupied by
                    nonimmigrant aliens. The plan shall include a timetable for
                    accomplishing the replacement of nonimmigrant aliens with
                    qualified citizens, CNMI permanent residents, and U.S.
                    permanent residents until the workforce participation
                    objective is met.
                </p>
            </div>
            <p class="mb-2 text-lg font-medium">Why do we collect it?</p>
            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <p class="mb-6 text-base font-normal">
                    Based on U.S. Pub L. 115-218 (NMI-US Workforce Act) “(i) BY
                    GOVERNOR. -Not later than 60 days before the end of each
                    calendar year, the Governor shall submit a report to the
                    Secretary that identifies the ratio between United States
                    workers and other workers in the Commonwealth's workforce
                    based on income tax filings with the Commonwealth for the
                    tax year.
                </p>
                <p class="mb-3 text-lg font-normal">
                    <label class="text-gray-800">
                        Download the Workforce Plan template
                    </label>
                    <a
                        class="text-base font-medium text-blue-600"
                        href="/WP_2023.xlsx"
                        download
                        >HERE</a
                    >.
                </p>

                <p class="text-lg font-normal">
                    <label class="text-gray-800">
                        Download the Workforce Listing template
                    </label>
                    <a
                        class="text-base font-medium text-blue-600"
                        href="/TWL_2023.xlsx"
                        download
                        >HERE</a
                    >.
                </p>
            </div>
            <p class="mb-2 text-lg font-medium">
                Guidance of filling out the templates:
            </p>
            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-base font-normal">
                        <span>
                            a. Directory of Nonimmigrant Visa Categories:
                            <a
                                href="https://travel.state.gov/content/travel/en/us-visas/visa-information-resources/all-visa-categories.html"
                            >
                                https://travel.state.gov/content/travel/en/us-visas/visa-information-resources/all-visa-categories.html
                            </a>
                            Instructions may be found at:
                            <a
                                href="https://www.dof.gov.mp/division-forms/revenue-taxation/instructions/2021-w2-3710-supplemental-ins.pdf"
                                >2021-w2-3710-supplemental-ins.pdf</a
                            >
                        </span>
                    </li>
                    <li class="text-base font-normal">
                        b. Correct entries for Visa type:
                        <a
                            href="https://finance.gov.mp/division-forms/revenue-taxation/instructions/2022-pub-ioc.pdf"
                            >2022-pub-ioc.pdf</a
                        >
                    </li>
                    <li class="text-base font-normal">
                        c. Major and minor SOC codes: www.onetonline.org
                        Remember: Completing the Certification section is
                        equivalent to submitting an official document to the
                        Department of Labor.
                    </li>
                </ul>
            </div>

            <p class="mb-2 text-lg font-medium">
                To reduce reporting errors or violations, ensure that the
                preparer/submitter:
            </p>

            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-base font-normal">
                        (1) Validates all the information entered into the
                        document.
                    </li>
                    <li class="text-base font-normal">
                        (2) Endorses the document prior to submission.
                    </li>
                    <li class="text-base font-normal">
                        (3) Submits the document in a timely manner.
                    </li>
                </ul>
            </div>

            <!-- title -->
            <p class="mb-2 text-lg font-medium">
                Failure to endorse the document may result in several actions:
            </p>

            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <p class="mb-6 text-base font-normal">
                    The TWL will be rejected by the Department; Considered as a
                    “non-submission”, and therefore non-compliant; Flagged for
                    incompletion, causing processing delays; and Delays in other
                    DOL requests (e.g., JVA certification, Good-standing, etc.).
                </p>
            </div>

            <!-- title -->
            <p class="mb-2 text-lg font-medium">General Tips:</p>

            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-base font-normal">
                        The Department accepts TWLs and WPs in Excel format
                        only.
                    </li>
                    <li class="text-base font-normal">
                        Use your resources: TWL Manual, WP Manual and W-2CM
                        Instructions.
                    </li>
                </ul>
            </div>

            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-lg font-normal">
                        REMEMBER: The TWL is submitted every quarter.
                    </li>
                    <li class="text-lg font-normal">
                        REMEMBER: The WP is submitted only once a year, unless
                        there is a change of status in your foreign national
                        worker workforce. Best time to submit the WP is at the
                        beginning of the quarter. For example, 1st quarter. New
                        and Old companies submitting reports for the first time
                        shall submit the TWL and a copy of the business license.
                    </li>
                </ul>
            </div>

            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-base font-normal">
                        If the workforce is a combination of US and foreign
                        workers, or all foreign workers, then WP shall be
                        submitted as well.
                    </li>
                    <li class="text-base font-normal">
                        Do not submit WP if all workers are (US eligible) for
                        example LPR, FSM, FAS and US. Submit your TWL and WP on
                        time.
                    </li>
                </ul>
            </div>

            <!-- title -->
            <p class="mb-2 text-lg font-medium">
                Contact us for more information:
            </p>

            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-base font-normal">MAILING ADDRESS</li>
                    <li class="text-base font-normal">
                        CNMI Department of Labor
                    </li>
                    <li class="text-base font-normal">
                        ATTN: Division of Employment Services - Statistics P.O.
                        Box 10007, Bldg. #1334, Ascencion Drive Capitol Hill,
                        Saipan, MP 96950
                    </li>
                </ul>
            </div>

            <!-- title -->
            <p class="mb-2 text-lg font-medium">PHONE CONTACTS</p>
            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-base font-normal">Main: (670) 322-9935</li>
                    <li class="text-base font-normal">
                        Alternate: (670) 322-9947
                    </li>
                </ul>
            </div>
            <!-- title -->
            <p class="mb-2 text-lg font-medium">EMAIL ADDRESSES</p>
            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <ul>
                    <li class="text-base font-normal">
                        Eugene Tebuteb: e.tebuteb@dol.gov.mp
                    </li>
                    <li class="text-base font-normal">
                        Mariana Coates: mcoates.dol@gmail.com
                    </li>
                    <li class="text-base font-normal">
                        Elaine Rosario: elainerosario.dol@gmail.com
                    </li>
                    <li class="text-base font-normal">
                        Labian Muna: labian.muna@gmail.com
                    </li>
                </ul>
            </div>
            <div
                class="mb-8 py-4 px-4 bg-gradient-to-r from-blue-200 to-blue-200 rounded"
            >
                <p class="mb-6 text-base font-normal">
                    Ready to submit your report? <Link class="text-medium">Click Here</Link> 
                </p>
                <p class="mb-6 text-base font-normal">
                    Do you need to update your company information? <Link class="text-medium">Click Here</Link> 
                </p>
            </div>
        </div>
    </fieldset>
</template>
