# Farm Management System (FMS) Data Model Diagram

This document contains the corrected Eraser.io Diagram-as-Code (DSL) representing the database schema for the Farm Management System. 

The layout has been redesigned to flow horizontally (sideways) rather than vertically.

### How this was achieved:
1. **Dissolved Giant Group:** The massive `Reference_Tables` group (which had 18 tables and forced a huge vertical column) was split. Each lookup table has been placed directly into its respective functional area (e.g. `service_types` inside `Reproductive_Area`), making the groups balanced.
2. **Reordered DSL Definition:** Defined lookup/metadata groups first, then the central `livestock` table, followed by children/event groups.
3. **Optimized Relationship Directions:** Changed relation definitions to flow left-to-right (`parent.id < child.foreign_key`), forcing lookup tables to remain on the left and event/historical tables to branch out to the right.

## Eraser.io DSL Code

Copy the code below and paste it into the **Diagram-as-Code** editor in [Eraser.io](https://www.eraser.io/).

```eraser
notation crows-foot
title Farm Management System (FMS) Data Model

// ===== CONFIGURATION =====
direction right

// ===== CENTRAL NODE =====
livestock [icon: database, color: green] {
  id string pk
  brand_number string
  electronic_code string
  name string
  entry_date date
  birth_date date
  general_comment string
  tits int
  is_enabled boolean
  is_alive boolean
  entry_cause_id string fk
  state enum
  animal_category enum
  breed_id string fk
  color_id string fk
  classification_id string fk
  owner_id string fk
  technician_id string fk
  batch_id string fk
  paddock_id string fk
  father_id string fk
  mother_id string fk
  adoptive_mother_id string fk
  receiving_mother_id string fk
}

// ===== GROUPS =====

group Livestock_Metadata {
  entry_causes [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  classifications [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  colors [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  breeds [icon: link, color: gray] {
    id string pk
    code string
    name string
  }
}

group Actors_and_Management {
  users [icon: user, color: blue] {
    id string pk
    name string
    email string
    password string
  }

  owners [icon: briefcase, color: purple] {
    id string pk
    code string
    name string
  }

  technicians [icon: link, color: blue] {
    id string pk
    code string
    name string
    type string
  }

  batches [icon: link, color: purple] {
    id string pk
    code string
    name string
  }

  paddocks [icon: map, color: green] {
    id string pk
    code string
    name string
    capacity int
    area decimal
  }
}

group Reproductive_Area {
  births [icon: link, color: green] {
    id string pk
    mother_id string fk
    birth_date date
    postbirth_revision_date date
    birth_type_id string fk
    technician_id string fk
  }

  aborts [icon: link, color: red] {
    id string pk
    livestock_id string fk
    made_at timestamp
    abort_type_id string fk
    technician_id string fk
  }

  services [icon: heart, color: red] {
    id string pk
    female_id string fk
    parentable_type string
    parentable_id string
    technician_id string fk
    service_type_id string fk
    made_at timestamp
  }

  newborns [icon: link, color: green] {
    id string pk
    birth_id string fk
    newborn_type_id string fk
    livestock_id string fk
  }

  teasings [icon: search, color: blue] {
    id string pk
    detected_at timestamp
    livestock_id string fk
    technician_id string fk
  }

  birth_types [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  abort_types [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  service_types [icon: heart, color: gray] {
    id string pk
    code string
    name string
  }

  newborn_types [icon: link, color: gray] {
    id string pk
    code string
    name string
  }
}

group Genetics_Area {
  semen_batches [icon: database, color: blue] {
    id string pk
    code string
    name string
    description string
    livestock_id string fk
    technician_id string fk
  }

  embrion_batches [icon: database, color: purple] {
    id string pk
    code string
    name string
    description string
    mother_id string fk
    father_id string fk
    technician_id string fk
  }

  extractions [icon: download, color: blue] {
    id string pk
    geneticable_type string
    geneticable_id string
    made_at timestamp
    technician_id string fk
    extraction_type_id string fk
  }

  extraction_types [icon: download, color: gray] {
    id string pk
    code string
    name string
  }
}

group Clinical_and_Health_Area {
  clinic_histories [icon: link, color: red] {
    id string pk
    code string
    name string
    description string
    attributes json
    livestock_id string fk
    technician_id string fk
  }

  clinic_history_treatments [icon: link, color: red] {
    id string pk
    clinic_history_id string fk
    clinical_treatment_id string fk
  }

  clinic_history_diagnostics [icon: link, color: red] {
    id string pk
    clinic_history_id string fk
    clinic_diagnostic_id string fk
  }

  treatment_applications [icon: link, color: red] {
    id string pk
    livestock_id string fk
    clinical_treatment_id string fk
    supply_id string fk
    dose_number int
    quantity decimal
    scheduled_date date
    applied_at timestamp
    applied_by_id string fk
    clinic_history_id string fk
    sanitary_plan_id string fk
  }

  revisions [icon: search, color: blue] {
    id string pk
    livestock_id string fk
    made_at timestamp
    revision_result enum
    revision_type_id string fk
    technician_id string fk
  }

  clinical_treatment_supplies [icon: link, color: orange] {
    id string pk
    supply_id string fk
    quantity decimal
    clinical_treatment_id string fk
  }

  sanitary_plans [icon: shield, color: orange] {
    id string pk
    name string
    description string
  }

  clinic_diagnostics [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  clinical_treatments [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  revision_types [icon: search, color: gray] {
    id string pk
    code string
    name string
  }
}

group Inventory_and_Supplies {
  supplies [icon: box, color: orange] {
    id string pk
    code string
    name string
    description string
    supply_type_id string fk
  }

  products [icon: package, color: orange] {
    id string pk
    code string
    name string
    description string
    attributes json
    product_type_id string fk
  }

  movement_kardex [icon: repeat, color: orange] {
    id string pk
    item_type string
    item_id string
    event_type string
    event_id string
    type enum
    quantity decimal
    date date
  }

  product_movements [icon: truck, color: orange] {
    id string pk
    product_id string fk
    type enum
    made_at timestamp
    attributes json
  }

  supply_movements [icon: truck, color: orange] {
    id string pk
    supply_id string fk
    type enum
    made_at timestamp
    attributes json
  }

  supply_types [icon: box, color: gray] {
    id string pk
    code string
    name string
  }

  product_types [icon: package, color: gray] {
    id string pk
    code string
    name string
  }
}

group Logging_and_Media {
  events [icon: zap, color: yellow] {
    id string pk
    livestock_id string fk
    eventable_type string
    eventable_id string
    made_at timestamp
  }

  growths [icon: trending-up, color: green] {
    id string pk
    made_at timestamp
    weight decimal
    height decimal
    length decimal
    thoracic_width decimal
    growthable_type string
    growthable_id string
    growth_type_id string fk
    livestock_id string fk
    technician_id string fk
  }

  milkings [icon: link, color: blue] {
    id string pk
    livestock_id string fk
    technician_id string fk
    made_at timestamp
    milking_type_id string fk
    first_weight decimal
    second_weight decimal
    third_weight decimal
  }

  images [icon: image, color: purple] {
    id string pk
    name string
    path string
    description string
    livestock_id string fk
  }

  comments [icon: link, color: yellow] {
    id string pk
    text string
    livestock_id string fk
    commentable_type string
    commentable_id string
  }

  outcomes [icon: link, color: red] {
    id string pk
    made_at timestamp
    outcome_type_id string fk
    livestock_id string fk
  }

  growth_types [icon: trending-up, color: gray] {
    id string pk
    code string
    name string
  }

  milking_types [icon: link, color: gray] {
    id string pk
    code string
    name string
  }

  outcome_types [icon: link, color: gray] {
    id string pk
    code string
    name string
  }
}

group Certificates_and_Movements {
  certificates [icon: link, color: yellow] {
    id string pk
    certificate_number string
    issue_date date
    expiry_date date
    file_path string
  }

  livestock_certificates [icon: link, color: yellow] {
    id string pk
    livestock_id string fk
    certificate_id string fk
  }

  batch_certificates [icon: link, color: yellow] {
    id string pk
    batch_id string fk
    certificate_id string fk
  }

  paddock_movements [icon: link, color: green] {
    id string pk
    livestock_id string fk
    paddock_id string fk
    made_at timestamp
  }

  batch_movements [icon: link, color: purple] {
    id string pk
    batch_id string fk
    livestock_id string fk
    made_at timestamp
    attributes json
  }

  batch_paddock_movements [icon: link, color: purple] {
    id string pk
    batch_id string fk
    paddock_id string fk
    made_at timestamp
  }
}

// ===== POLYMORPHIC INTERFACE NODES =====

Kardex_Item [icon: link, color: white] {
  interface polymorphic
}

Kardex_Event [icon: zap, color: white] {
  interface polymorphic
}

Eventable [icon: link, color: white] {
  interface polymorphic
}

Parentable [icon: link, color: white] {
  interface polymorphic
}

Geneticable [icon: link, color: white] {
  interface polymorphic
}

Commentable [icon: link, color: white] {
  interface polymorphic
}

Growthable [icon: link, color: white] {
  interface polymorphic
}

// ===== RELATIONSHIPS (Flows Left-to-Right) =====

// References to Livestock (Parents on the left, Livestock in center)
entry_causes.id < livestock.entry_cause_id
breeds.id < livestock.breed_id
colors.id < livestock.color_id
classifications.id < livestock.classification_id
owners.id < livestock.owner_id
technicians.id < livestock.technician_id
batches.id < livestock.batch_id
paddocks.id < livestock.paddock_id
livestock.id < livestock.father_id
livestock.id < livestock.mother_id
livestock.id < livestock.adoptive_mother_id
livestock.id < livestock.receiving_mother_id

// Genetic material relationships (Livestock on left, batches/extractions on right)
livestock.id < semen_batches.livestock_id
technicians.id < semen_batches.technician_id
livestock.id < embrion_batches.mother_id
livestock.id < embrion_batches.father_id
technicians.id < embrion_batches.technician_id

// Inventory relationships
supply_types.id < supplies.supply_type_id
product_types.id < products.product_type_id

// Operational event relationships (Livestock/Actors on left, events on right)
livestock.id < births.mother_id
birth_types.id < births.birth_type_id
technicians.id < births.technician_id

livestock.id < aborts.livestock_id
abort_types.id < aborts.abort_type_id
technicians.id < aborts.technician_id

livestock.id < revisions.livestock_id
revision_types.id < revisions.revision_type_id
technicians.id < revisions.technician_id

livestock.id < services.female_id
technicians.id < services.technician_id
service_types.id < services.service_type_id

technicians.id < extractions.technician_id
extraction_types.id < extractions.extraction_type_id

livestock.id < treatment_applications.livestock_id
clinical_treatments.id < treatment_applications.clinical_treatment_id
supplies.id < treatment_applications.supply_id
technicians.id < treatment_applications.applied_by_id
clinic_histories.id < treatment_applications.clinic_history_id
sanitary_plan_id.id < treatment_applications.sanitary_plan_id

// Event and logging relationships
livestock.id < events.livestock_id

births.id < newborns.birth_id
newborn_types.id < newborns.newborn_type_id
livestock.id < newborns.livestock_id

growth_types.id < growths.growth_type_id
livestock.id < growths.livestock_id
technicians.id < growths.technician_id

livestock.id < milkings.livestock_id
technicians.id < milkings.technician_id
milking_types.id < milkings.milking_type_id

livestock.id < images.livestock_id

livestock.id < comments.livestock_id

products.id < product_movements.product_id

supplies.id < supply_movements.supply_id

outcome_types.id < outcomes.outcome_type_id
livestock.id < outcomes.livestock_id

batches.id < batch_movements.batch_id
livestock.id < batch_movements.livestock_id

livestock.id < paddock_movements.livestock_id
paddocks.id < paddock_movements.paddock_id

batches.id < batch_paddock_movements.batch_id
paddocks.id < batch_paddock_movements.paddock_id

supplies.id < clinical_treatment_supplies.supply_id
clinical_treatments.id < clinical_treatment_supplies.clinical_treatment_id

livestock.id < teasings.livestock_id
technicians.id < teasings.technician_id

clinic_histories.id < clinic_history_treatments.clinic_history_id
clinical_treatments.id < clinic_history_treatments.clinical_treatment_id

clinic_histories.id < clinic_history_diagnostics.clinic_history_id
clinic_diagnostics.id < clinic_history_diagnostics.clinic_diagnostic_id

livestock.id < livestock_certificates.livestock_id
certificates.id < livestock_certificates.certificate_id

batches.id < batch_certificates.batch_id
certificates.id < batch_certificates.certificate_id

livestock.id < clinic_histories.livestock_id
technicians.id < clinic_histories.technician_id

// Polymorphic: Kardex_Item
movement_kardex > Kardex_Item
Kardex_Item - semen_batches
Kardex_Item - embrion_batches
Kardex_Item - supplies
Kardex_Item - products

// Polymorphic: Kardex_Event
movement_kardex > Kardex_Event
Kardex_Event - extractions
Kardex_Event - treatment_applications
Kardex_Event - services

// Polymorphic: Eventable
events > Eventable
Eventable - births
Eventable - aborts
Eventable - revisions
Eventable - services
Eventable - extractions
Eventable - treatment_applications

// Polymorphic: Parentable
services > Parentable
Parentable - livestock
Parentable - semen_batches
Parentable - embrion_batches

// Polymorphic: Geneticable
extractions > Geneticable
Geneticable - semen_batches
Geneticable - embrion_batches

// Polymorphic: Commentable
comments > Commentable
Commentable - livestock
Commentable - clinic_histories

// Polymorphic: Growthable
growths > Growthable
Growthable - livestock
```
