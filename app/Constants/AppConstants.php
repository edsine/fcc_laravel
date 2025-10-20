<?php

namespace App\Constants;

class AppConstants
{
    public const ROWS_PER_PAGE = 50;

    public const DEFAULT_SMS_NOTIFICATION_MESSAGE = "Federal Character Commission Has Sent You An Email. Please Check Your Email Inbox or Spam Folder And Respond Accordingly.";
    public const DEFAULT_SMS_NOTIFICATION_SUFFIX = "Has Sent You An Email. Please Check Your Email Inbox or Spam Folder And Respond Accordingly.";
    public const FCC_SMS_SENDER = "FED CHAR CO";
    public const FCC_EMAIL_SENDER_NAME = "Federal Character Commission Portal";
    public const FCC_EMAIL_SENDER_EMAIL = "saleahmadu@gmail.com";

    public const FCC = 'FCC';
    public const MDA = 'MDA';

    public const DEV_UPLOAD_BASE_DIR = "C:" . DIRECTORY_SEPARATOR . "FCC_DATA_PROCESSING_PORTAL" . DIRECTORY_SEPARATOR;
    public const DEV_PUBLIC_UPLOAD_BASE_DIR = "C:" . DIRECTORY_SEPARATOR . "Apache24" . DIRECTORY_SEPARATOR . "htdocs" . DIRECTORY_SEPARATOR . "fcc_portal_docs" . DIRECTORY_SEPARATOR;

    public const PROD_UPLOAD_BASE_DIR = DIRECTORY_SEPARATOR . "home" . DIRECTORY_SEPARATOR . "federalc" . DIRECTORY_SEPARATOR . "FCC_PORTAL" . DIRECTORY_SEPARATOR;
    public const PROD_PUBLIC_UPLOAD_BASE_DIR = DIRECTORY_SEPARATOR . "home" . DIRECTORY_SEPARATOR . "federalc" . DIRECTORY_SEPARATOR . "public_html" . DIRECTORY_SEPARATOR . "fcc_portal_docs" . DIRECTORY_SEPARATOR;

    public const DEV_NOMINAL_ROLL_UPLOAD_DIR = self::DEV_UPLOAD_BASE_DIR . "NOMINAL_ROLL" . DIRECTORY_SEPARATOR;
    public const PROD_NOMINAL_ROLL_UPLOAD_DIR = self::PROD_UPLOAD_BASE_DIR . "NOMINAL_ROLL" . DIRECTORY_SEPARATOR;

    public const DEV_DOWNLOAD_MANAGER_UPLOAD_DIR = self::DEV_UPLOAD_BASE_DIR . "downloads" . DIRECTORY_SEPARATOR;
    public const PROD_DOWNLOAD_MANAGER_UPLOAD_DIR = self::PROD_UPLOAD_BASE_DIR . "downloads" . DIRECTORY_SEPARATOR;

    public const DEV_VACANCY_UPLOAD_DIR = self::DEV_PUBLIC_UPLOAD_BASE_DIR . "vacancy" . DIRECTORY_SEPARATOR;
    public const PROD_VACANCY_UPLOAD_DIR = self::PROD_PUBLIC_UPLOAD_BASE_DIR . "vacancy" . DIRECTORY_SEPARATOR;

    public const DEV_PROFILE_PHOTO_UPLOAD_DIR = self::DEV_PUBLIC_UPLOAD_BASE_DIR . "user_profile" . DIRECTORY_SEPARATOR;
    public const PROD_PROFILE_PHOTO_UPLOAD_DIR = self::PROD_PUBLIC_UPLOAD_BASE_DIR . "user_profile" . DIRECTORY_SEPARATOR;

    public const DEV_UPLOAD_TRASH_DIR = self::DEV_UPLOAD_BASE_DIR . "TRASH" . DIRECTORY_SEPARATOR;
    public const PROD_UPLOAD_TRASH_DIR = self::PROD_UPLOAD_BASE_DIR . "trash" . DIRECTORY_SEPARATOR;

    public const DEV_PUBLIC_UPLOAD_TRASH_DIR = self::DEV_PUBLIC_UPLOAD_BASE_DIR . "trash" . DIRECTORY_SEPARATOR;
    public const PROD_PUBLIC_UPLOAD_TRASH_DIR = self::PROD_PUBLIC_UPLOAD_BASE_DIR . "trash" . DIRECTORY_SEPARATOR;

    public const DEV_PUBLIC_VACANCY_UPLOAD_URL = 'http://localhost/fcc_portal_docs/vacancy/';
    public const PROD_PUBLIC_VACANCY_UPLOAD_URL = 'http://federalcharacter.gov.ng/fcc_portal_docs/vacancy/';

    public const DEV_PUBLIC_UPLOAD_TRASH_URL = 'http://localhost/fcc_portal_docs/trash/';
    public const PROD_PUBLIC_UPLOAD_TRASH_URL = 'http://federalcharacter.gov.ng/fcc_portal_docs/trash/';

    public const DEV_PUBLIC_PROFILE_URL = 'http://localhost/fcc_portal_docs/user_profile/';
    public const PROD_PUBLIC_PROFILE_URL = 'http://federalcharacter.gov.ng/fcc_portal_docs/user_profile/';

    public const TO_MDA_DESK_OFFICERS = "TO_MDA_DESK_OFFICERS";

    //SYSTEM RESERVED ROLES
    public const ROLE_USER = "ROLE_USER";
    public const ROLE_SUPER_ADMIN = 4000;
    public const ROLE_MIS_HEAD = 5000;


    public const ROLE_MDA_ADMIN = 5002;

    public const ROLE_FCC_DESK_OFFICER_FEDERAL = 5001;
    public const ROLE_FCC_COMMISSIONER = 5003;
    public const ROLE_FCC_COMMITTEE_SECRETARY = 5004;
    public const ROLE_FCC_COMMITTEE_CHAIRMAN = 5008;

    public const FCC_USER = "FCC_USER";
    public const MDA_USER = "MDA_USER";

    public const DUPLICATE_USERNAME = 'DUPLICATE_USERNAME';
    public const DUPLICATE_NAME = 'DUPLICATE_NAME';
    public const DUPLICATE_EMAIL = 'DUPLICATE_EMAIL';
    public const DUPLICATE_PRIMARY_PHONE = 'DUPLICATE_PRIMARY_PHONE';
    public const DUPLICATE_SECONDARY_PHONE = 'DUPLICATE_SECONDARY_PHONE';

    public const DUPLICATE = 'DUPLICATE';
    public const DUPLICATE_CODE = 'DUPLICATE_CODE';
    public const DUPLICATE_YEAR = 'DUPLICATE_YEAR';
    public const DUPLICATE_DESC = 'DUPLICATE_DESC';
    public const DUPLICATE_GUID = 'DUPLICATE_GUID';
    public const DUPLICATE_FCC_DESK_OFFICER_COMMITTEE = 'DUPLICATE_FCC_DESK_OFFICER_COMMITTEE';

    public const CANNOT_UPDATE = 'CANNOT_UPDATE';

    public const ACTIVE = 'ACTIVE';
    public const INACTIVE = 'INACTIVE';
    public const EXIT = 'EXIT';

    public const CANCEL = 'CANCEL';
    public const CANCELLED = 'CANCELLED';

    public const NEW = 'NEW';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    public const REQUEST = 'REQUEST';

    public const OPEN_NOTIFICATION = 'OPEN_NOTIFICATION';

    public const CAREER_CIVIL_SERVANT = 'CAREER_CIVIL_SERVANT';
    public const POLITICAL_OFFICE_HOLDER = 'POLITICAL_OFFICE_HOLDER';

    //cache item keys
    public const KEY_CACHED_NATIONALITY_CODES = 'app.cached_nationality_codes';
    public const KEY_CACHED_GEO_POLITICAL_ZONES = 'app.cached_geo_political_zones';
    public const KEY_CACHED_NIGERIAN_STATES = 'app.cached_nigerian_states';
    public const KEY_CACHED_STATE_LGAS = 'app.cached_state_lgas';
    public const KEY_CACHED_GENDER = 'app.cached_gender';
    public const KEY_CACHED_SUBMISSION_STATUS = 'app.cached_submission_status';
    public const KEY_CACHED_FCC_ROLES = 'app.cached_fcc_roles';
    public const KEY_CACHED_MDA_ROLES = 'app.cached_mda_roles';

    public const KEY_CACHED_ROLES = 'app.cached_roles';
    public const KEY_CACHED_ROLE_CATEGORIES = 'app.cached_role_categories';
    public const KEY_CACHED_COMMITTEES = 'app.cached_committees';
    public const KEY_CACHED_DEPARTMENTS = 'app.cached_departments';
    public const KEY_CACHED_FCC_USERS = 'app.cached_fcc_users';
    public const KEY_CACHED_ORGANIZATIONS = 'app.cached_organizations';
    public const KEY_CACHED_CLIENT_ORGANIZATION = 'app.cached_client_organization';
    public const KEY_CACHED_SUBMISSION_YEARS = 'app.cached_submission_years';
    public const KEY_CACHED_SYSTEM_PRIVILEGES = 'app.cached_system_privileges';
    public const KEY_CACHED_SYSTEM_ROLE_PRIVILEGES = 'app.cached_system_role_privileges';

    public const KEY_CACHED_STATIC_CONTENT = 'app.cached_static_content';
    public const KEY_CACHED_POLITICAL_OFFICE_GRADE_LEVELS = 'app.cached_political_office_grade_levels';
    public const KEY_CACHED_CAREER_CIVIL_SERVANT_GRADE_LEVELS = 'app.cached_career_civil_servant_grade_levels';

    public const KEY_CACHED_MARITAL_STATUS = 'app.cached_marital_status';
    public const KEY_CACHED_SUBMISSION_TYPES = 'app.cached_submission_types';
    public const KEY_CACHED_APPROVAL_STATUS = 'app.cached_approval_status';
    public const KEY_CACHED_ORGANIZATION_CATEGORY_TYPES = 'app.cached_organization_category_types';
    public const KEY_CACHED_CBI_REPORT_GL_CATEGORIES = 'app.cached_cbi_report_gl_categories';
    public const KEY_CACHED_DOWNLOAD_CATEGORIES = 'app.cached_download_categories';

    //user profile types
    public const FCC_USER_PROFILE = 'FCC_USER';
    public const MDA_USER_PROFILE = 'MDA_USER';

    public const ALERT_SUCCESS = 'success';
    public const ALERT_INFO = 'info';
    public const ALERT_WARNING = 'warning';
    public const ALERT_DANGER = 'danger';

    public const CBI_REQUEST_TYPE_NORMAL = 'NORMAL';
    public const CBI_REQUEST_TYPE_RECRUITMENT = 'RECRUITMENT';

    public const Y = 'Y';
    public const N = 'N';

    public const YES = 'YES';
    public const NO = 'NO';

    public const FEDERAL = "FEDERAL";
    public const STATE = "STATE";
    public const LOCAL_GOVERNMENT = "LOCAL_GOVERNMENT";

    public const FEDERAL_MINISTRY_ESTABLISHMENT = "FEDERAL_MINISTRY";
    public const FEDERAL_PARASTATAL_ESTABLISHMENT = "FEDERAL_PARASTATAL";
    public const STATE_MINISTRY_ESTABLISHMENT = "STATE_MINISTRY";
    public const STATE_PARASTATAL_ESTABLISHMENT = "STATE_PARASTATAL";

    public const RECIPIENT_EMAILS_ADDRESSES = 'recipient_email_addresses';
    public const RECIPIENT_PHONE_NUMBERS = 'recipient_phone_numbers';
    public const FEDERAL_MDA = 'FEDERAL_MDA';


    public const ACADEMIC_ESTABLISHMENT = 1;

    public const PENDING = 'PENDING';
    public const APPROVED = 'APPROVED';
    public const DECLINED = 'DECLINED';
    public const FAILED = 'FAILED';
    public const PASSED = 'PASSED';
    public const STARTED = 'STARTED';
    public const COMPLETED = 'COMPLETED';
    public const CONFIRMED = 'CONFIRMED';
    public const RECOMMENDED = 'RECOMMENDED';
    public const FATAL_ERROR = 'FATAL_ERROR';
    public const INCOMPLETE = 'INCOMPLETE';

    public const MAIN_SUBMISSION = 'MAIN_SUBMISSION';
    public const QUARTERLY_RETURN = 'QUARTERLY_RETURN';

    public const FIRST_SUBMISSION = "FIRST_SUBMISSION";
    public const ADDITIONAL_SUBMISSION = "ADDITIONAL_SUBMISSION";
    public const FIRST_NON_SEQUENTIAL_SUBMISSION = "FIRST_NON_SEQUENTIAL_SUBMISSION";

    public const LONG_LIST = "LONG_LIST";
    public const SHORT_LIST = "SHORT_LIST";
    public const COC_CANDIDATE_LIST = "COC_CANDIDATE_LIST";
    public const COC_CANDIDATE_APPOINTMENT_LIST = "COC_CANDIDATE_APPOINTMENT_LIST";

    public const INVALID_EMPLOYEE_STATUS = "INVALID_EMPLOYEE_STATUS";
    public const INVALID_EMPLOYEE_NUMBER = "INVALID_EMPLOYEE_NUMBER";
    public const INVALID_NAME = "INVALID_NAME";
    public const INVALID_SURNAME = "INVALID_SURNAME";
    public const INVALID_FIRST_NAME = "INVALID_FIRST_NAME";
    public const INVALID_OTHER_NAME = "INVALID_OTHER_NAME";
    public const INVALID_ADDRESS = "INVALID_ADDRESS";
    public const INVALID_CENTER = "INVALID_CENTER";
    public const INVALID_PHONE = "INVALID_PHONE";
    public const INVALID_EMAIL = "INVALID_EMAIL";
    public const INVALID_POST_APPLIED = "INVALID_POST_APPLIED";
    public const INVALID_UNIVERSITY = "INVALID_UNIVERSITY";
    public const INVALID_COURSE = "INVALID_COURSE";
    public const INVALID_DEGREE = "INVALID_DEGREE";
    public const INVALID_APPOINTMENT_STATUS = "INVALID_APPOINTMENT_STATUS";
    public const INVALID_NATIONALITY_CODE = "INVALID_NATIONALITY_CODE";
    public const INVALID_STATE_OF_ORIGIN = "INVALID_STATE_OF_ORIGIN";
    public const INVALID_DATE_OF_BIRTH = "INVALID_DATE_OF_BIRTH";
    public const INVALID_DATE_OF_EMPLOYMENT = "INVALID_DATE_OF_EMPLOYMENT";
    public const INVALID_DATE_OF_PRESENT_APPOINTMENT = "INVALID_DATE_OF_PRESENT_APPOINTMENT";
    public const INVALID_GRADE_LEVEL = "INVALID_GRADE_LEVEL";
    public const INVALID_DESIGNATION = "INVALID_DESIGNATION";
    public const INVALID_STATE_OF_LOCATION = "INVALID_STATE_OF_LOCATION";
    public const INVALID_GENDER = "INVALID_GENDER";
    public const INVALID_MARITAL_STATUS = "INVALID_MARITAL_STATUS";
    public const INVALID_LGA = "INVALID_LGA";
    public const INVALID_GEO_POLITICAL_ZONE = "INVALID_GEO_POLITICAL_ZONE";
    public const INVALID_PHYSICALLY_CHALLENGED_STATUS = "INVALID_PHYSICALLY_CHALLENGED_STATUS";
    public const INVALID_QUARTERLY_RETURN_EMPLOYMENT_STATUS = "INVALID_QUARTERLY_RETURN_EMPLOYMENT_STATUS";

    public const INVALID_LGA_OF_ORIGIN = "INVALID_LGA_OF_ORIGIN";
    public const INVALID_SENATORIAL_DISTRICT = "INVALID_SENATORIAL_DISTRICT";
    public const INVALID_LGA_OF_DEPLOYMENT = "INVALID_LGA_OF_DEPLOYMENT";

    public const INVALID_NOT_IN_LONG_LIST = "INVALID_NOT_IN_LONG_LIST";
    public const INVALID_DUPLICATE_RECORD = "INVALID_DUPLICATE_RECORD";
    public const INVALID_DUPLICATE_PREVIOUS_RECORD = "INVALID_DUPLICATE_PREVIOUS_RECORD";

    public const STATIC_CONTENT_FEDERAL_LEVEL_NOMINAL_ROLE_SUBMISSION_INSTRUCTIONS = 'FEDERAL_LEVEL_NOMINAL_ROLE_SUBMISSION_INSTRUCTIONS';
    public const STATIC_CONTENT_LOGIN_PAGE_APPLICATION_NAME = 'LOGIN_PAGE_APPLICATION_NAME';
    public const STATIC_CONTENT_NOMINAL_ROLE_DEMAND_NOTICE_MESSAGE = 'NOMINAL_ROLE_DEMAND_NOTICE_MESSAGE';
    public const STATIC_CONTENT_NOMINAL_ROLE_DEMAND_NOTICE_REMINDER_MESSAGE = 'NOMINAL_ROLE_DEMAND_NOTICE_REMINDER_MESSAGE';
    public const STATIC_CONTENT_STATE_LEVEL_NOMINAL_ROLE_SUBMISSION_INSTRUCTIONS = 'STATE_LEVEL_NOMINAL_ROLE_SUBMISSION_INSTRUCTIONS';

    public const GRADE_LEVEL_CHIEF_EXECUTIVES = '90';

    //ADMIN PRIVILEGES
    public const PRIV_CMS_MANAGE = "CMS_MANAGE";
    public const PRIV_COMMITTEE_LIST = "COMMITTEE_LIST";
    public const PRIV_COMMITTEE_MODIFY = "COMMITTEE_MODIFY";
    public const PRIV_FCC_USER_LIST = "FCC_USER_LIST";
    public const PRIV_FCC_USER_MODIFY = "FCC_USER_MODIFY";
    public const PRIV_FED_MDA_LIST = "FED_MDA_LIST";
    public const PRIV_FED_MDA_MODIFY = "FED_MDA_MODIFY";
    public const PRIV_MDA_USER_LIST = "MDA_USER_LIST";
    public const PRIV_MDA_USER_MODIFY = "MDA_USER_MODIFY";
    public const PRIV_ROLE_MANAGE = "ROLE_MANAGE";
    public const PRIV_STATE_MDA_LIST = "STATE_MDA_LIST";
    public const PRIV_STATE_MDA_MODIFY = "STATE_MDA_MODIFY";

    //FCC PRIVILEGES
    public const PRIV_ASSIGNED_MDA_LIST = "ASSIGNED_MDA_LIST";
    public const PRIV_COMMITTEE_MDA_LIST = "COMMITTEE_MDA_LIST";
    public const PRIV_CONFIRM_FED_MDA_NOMINAL_ROLL_UPLOAD = "CONFIRM_FED_MDA_NOMINAL_ROLL_UPLOAD";
    public const PRIV_CONFIRM_STATE_MDA_NOMINAL_ROLL_UPLOAD = "CONFIRM_STATE_MDA_NOMINAL_ROLL_UPLOAD";

    //FED MDA PRIVILEGES
    public const PRIV_FED_MDA_UPLOAD_NOMINAL_ROLL = "FED_MDA_UPLOAD_NOMINAL_ROLL";
    public const PRIV_FED_MDA_UPLOAD_VACANCY = "FED_MDA_UPLOAD_VACANCY";

    //FEDERAL REPORTS PRIVILEGES
    public const PRIV_FED_MDA_CAREER_CHARACTER_BALANCING_INDEX = "FED_MDA_CAREER_CHARACTER_BALANCING_INDEX";
    public const PRIV_FED_MDA_CAREER_POST_DIST = "FED_MDA_CAREER_POST_DIST";
    public const PRIV_FED_MDA_NOMINAL_ROLL_QUERY = "FED_MDA_NOMINAL_ROLL_QUERY";

    //OTHER REPORTS PRIVILEGES
    public const PRIV_FED_CONSOLIDATED_CAREER_POST_DIST = "FED_CONSOLIDATED_CAREER_POST_DIST";
    public const PRIV_FED_LEVEL_COMPARATIVE_DATA_ON_STAFF_DIST = "FED_LEVEL_COMPARATIVE_DATA_ON_STAFF_DIST";
    public const PRIV_FED_LEVEL_LIST_OF_CHIEF_EXECUTIVES = "FED_LEVEL_LIST_OF_CHIEF_EXECUTIVES";
    public const PRIV_FED_POLOH_BY_POST_AND_YEAR_DIST = "FED_POLOH_BY_POST_AND_YEAR_DIST";
    public const PRIV_FED_POLOH_POST_DIST = "FED_POLOH_POST_DIST";

    //REPORT MDA LIMIT PRIVILEGES
    public const PRIV_ALL_MDA_REPORT_SELECTION_LIMIT = "ALL_MDA_REPORT_SELECTION_LIMIT";
    public const PRIV_ASSIGNED_MDA_REPORT_SELECTION_LIMIT = "ASSIGNED_MDA_REPORT_SELECTION_LIMIT";
    public const PRIV_COMMITTEE_MDA_REPORT_SELECTION_LIMIT = "COMMITTEE_MDA_REPORT_SELECTION_LIMIT";
    public const PRIV_USER_MDA_REPORT_SELECTION_LIMIT = "USER_MDA_REPORT_SELECTION_LIMIT";

    //STATE MDA PRIVILEGES
    public const PRIV_STATE_MDA_UPLOAD_NOMINAL_ROLL = "STATE_MDA_UPLOAD_NOMINAL_ROLL";
    public const PRIV_STATE_MDA_UPLOAD_VACANCY = "STATE_MDA_UPLOAD_VACANCY";
}
