import { ListGuesser,
    FieldGuesser, 
    ResourceGuesser, 
    InputGuesser, 
    CreateGuesser,
    ShowGuesser,
    EditGuesser, } from "@api-platform/admin";
import { TextField , ReferenceField, ReferenceInput, AutocompleteInput, ArrayField, SingleFieldList, ChipField, NumberField, EmailField} from "react-admin";

const VoucherList = (props) => (
    <ListGuesser {...props}>
        <ReferenceField source="partnerId" reference="partners">
            <ChipField source="name" />
        </ReferenceField>
        <FieldGuesser source="name" />
        <FieldGuesser source="description" />
        <FieldGuesser source="limitUse" />
        <FieldGuesser source="price" />
        <FieldGuesser source="startDate" />
        <FieldGuesser source="endDate" />
    </ListGuesser>
    );
const VoucherShow = (props) => (
    <ShowGuesser {...props}>
        <ReferenceField source="partnerId" reference="partners">
            <ChipField source="name" />
        </ReferenceField>
        <FieldGuesser source="name" />
        <FieldGuesser source="description" />
        <FieldGuesser source="limitUse" />
        <FieldGuesser source="price" />
        <FieldGuesser source="startDate" />
        <FieldGuesser source="endDate" />
    </ShowGuesser>
    );
const VoucherCreate = (props) => (
    <CreateGuesser {...props}>
        <ReferenceInput source="partnerId" reference="partners">
            <AutocompleteInput optionText="name" label="partner" 
            filterToQuery={searchText => ({ name: searchText })}
            />
        </ReferenceInput>
        <InputGuesser source="name" />
        <InputGuesser source="description" />
        <InputGuesser source="limitUse" />
        <InputGuesser source="price" />
        <InputGuesser source="startDate" />
        <InputGuesser source="endDate" />
    </CreateGuesser>
    );
const VoucherEdit = (props) => (
    <EditGuesser {...props}>
        <ReferenceInput source="partnerId" reference="partners">
            <AutocompleteInput optionText="name" label="partner" 
            filterToQuery={searchText => ({ name: searchText })}
            />
        </ReferenceInput>
        <InputGuesser source="name" />
        <InputGuesser source="description" />
        <InputGuesser source="limitUse" />
        <InputGuesser source="price" />
        <InputGuesser source="startDate" />
        <InputGuesser source="endDate" />
    </EditGuesser>
    );
function Voucher(){
    return {
        list: VoucherList,
        show: VoucherShow,
        create: VoucherCreate,
        edit: VoucherEdit,
    }
}
export default Voucher;