import { ListGuesser,
    FieldGuesser, 
    InputGuesser, 
    CreateGuesser,
    ShowGuesser,
    EditGuesser, } from "@api-platform/admin";
import { ReferenceField, ReferenceInput, AutocompleteInput, ChipField, FunctionField} from "react-admin";

const UserVoucherList = (props) => (
    <ListGuesser {...props}>
        <ReferenceField source="userId" reference="users">
            <ChipField source="email" />
        </ReferenceField>
         <ReferenceField source="voucherId" reference="vouchers" label="Voucher" >
            <ReferenceField source="partnerId" reference="partners" label="Partner" >
                <ChipField source="name" />
            </ReferenceField>
            <FunctionField render={
                record => 
                    `${record.name},  limit: ${record.limitUse}   price: ${record.price} / ${new Date(record.startDate).toDateString()} - ${new Date(record.endDate).toDateString()}`
            } />
        </ReferenceField>
        <FieldGuesser source="claim" />
    </ListGuesser>
    );
    const VoucherShow = (props) => (
        <ShowGuesser {...props}>
        <ReferenceField source="userId" reference="users">
            <ChipField source="email" />
        </ReferenceField>
        <ReferenceField source="voucherId" reference="vouchers" label="Voucher" >
            <ReferenceField source="partnerId" reference="partners" label="Partner" >
                <ChipField source="name" />
            </ReferenceField>
            <FunctionField render={
                record => 
                    `${record.name},  limit: ${record.limitUse}   price: ${record.price} / ${new Date(record.startDate).toDateString()} - ${new Date(record.endDate).toDateString()}`
            } />
        </ReferenceField>
        <FieldGuesser source="claim" />
        </ShowGuesser>
        );
    const VoucherCreate = (props) => (
        <CreateGuesser {...props}>
            <ReferenceInput source="userId" reference="users">
                <AutocompleteInput optionText="email" label="user"
                filterToQuery={searchText => ({ email: searchText })}
                />
            </ReferenceInput>
            <ReferenceInput source="voucherId" reference="vouchers">
                <AutocompleteInput optionText="name" label="voucher"
                filterToQuery={searchText => ({ name: searchText })}
                />
            </ReferenceInput>
            <InputGuesser source="claim" />
        </CreateGuesser>
        );
    const VoucherEdit = (props) => (
        <EditGuesser {...props}>
        
             <ReferenceField source="userId" reference="users">
             <ChipField source="email" />
            </ReferenceField>
            <ReferenceField source="voucherId" reference="vouchers" label="Voucher" >
                <ReferenceField source="partnerId" reference="partners" label="Partner" >
                    <ChipField source="name" />
                </ReferenceField>
                <FunctionField render={
                    record => 
                        `${record.name},  limit: ${record.limitUse}   price: ${record.price} / ${new Date(record.startDate).toDateString()} - ${new Date(record.endDate).toDateString()}`
                } />
            </ReferenceField>
            <InputGuesser source="claim" />
        </EditGuesser>
        );

function UserVoucher(){
    return {
        list: UserVoucherList,
        show: VoucherShow,
        create: VoucherCreate,
        edit: VoucherEdit,
    }
}
export default UserVoucher;